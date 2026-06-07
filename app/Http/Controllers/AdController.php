<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdInteraction;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdController extends Controller
{
    public function index(Request $request): View
    {
        $ads = Ad::query()
            ->published()
            ->when($request->filled('category'), fn (Builder $query): Builder => $query->where('category', $request->string('category')->toString()))
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where(function (Builder $query) use ($term): void {
                    $query->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('location', 'like', $term);
                });
            })
            ->with('user.receivedReviews')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('ads.index', [
            'ads' => $ads,
            'heading' => __('app.browse_ads'),
            'showOwnerActions' => false,
        ]);
    }

    public function myAds(Request $request): View
    {
        $ads = Ad::query()
            ->whereBelongsTo($request->user())
            ->when($request->filled('category'), fn (Builder $query): Builder => $query->where('category', $request->string('category')->toString()))
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where(function (Builder $query) use ($term): void {
                    $query->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('location', 'like', $term);
                });
            })
            ->with('user.receivedReviews')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('ads.index', [
            'ads' => $ads,
            'heading' => __('app.my_ads'),
            'showOwnerActions' => true,
        ]);
    }

    public function show(Request $request, Ad $ad): View
    {
        abort_unless(
            $ad->isPublished() || $this->canManageAd($request, $ad),
            404,
        );

        $canManageAd = $this->canManageAd($request, $ad);
        $canSeeContact = $request->user()->canViewSellerContact() || $canManageAd;

        if (! $canManageAd) {
            $ad->interactions()->firstOrCreate([
                'user_id' => $request->user()->id,
                'type' => AdInteraction::TYPE_VIEW,
            ]);
        }

        return view('ads.show', [
            'ad' => $ad->loadMissing('user.receivedReviews'),
            'adAnalytics' => $canManageAd ? $this->analyticsFor($ad) : null,
            'canManageAd' => $canManageAd,
            'canSeeContact' => $canSeeContact,
            'phoneRevealed' => $canManageAd || $request->session()->has($this->phoneRevealSessionKey($ad)),
        ]);
    }

    public function revealPhone(Request $request, Ad $ad): RedirectResponse
    {
        abort_unless(
            $ad->isPublished() || $this->canManageAd($request, $ad),
            404,
        );

        $canManageAd = $this->canManageAd($request, $ad);

        if (! $request->user()->canViewSellerContact() && ! $canManageAd) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.unlock_contact_details'));
        }

        if (! $canManageAd) {
            $ad->interactions()->firstOrCreate([
                'user_id' => $request->user()->id,
                'type' => AdInteraction::TYPE_PHONE_REVEAL,
            ]);
        }

        $request->session()->put($this->phoneRevealSessionKey($ad), true);

        return redirect()
            ->route('ads.show', $ad)
            ->with('status', __('app.phone_revealed'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->user()->canPublishAds()) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.subscription_publish_locked'));
        }

        return view('ads.form', [
            'ad' => new Ad(['status' => Ad::STATUS_DRAFT, 'condition' => 'used']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->user()->canPublishAds()) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.subscription_publish_locked'));
        }

        $ad = $request->user()->ads()->create($this->validatedPayload($request));

        return redirect()
            ->route('ads.show', $ad)
            ->with('status', __('app.ad_published'));
    }

    public function edit(Request $request, Ad $ad): View
    {
        abort_unless($this->canManageAd($request, $ad), 403);

        return view('ads.form', [
            'ad' => $ad,
        ]);
    }

    public function update(Request $request, Ad $ad): RedirectResponse
    {
        abort_unless($this->canManageAd($request, $ad), 403);

        $status = $request->input('status', $ad->status);

        if ($status === Ad::STATUS_PUBLISHED && ! $ad->isPublished() && ! $request->user()->canPublishAds()) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.subscription_slot_unavailable'));
        }

        $ad->update($this->validatedPayload($request, $ad));

        return redirect()
            ->route('ads.show', $ad)
            ->with('status', __('app.ad_updated'));
    }

    public function markSold(Request $request, Ad $ad): RedirectResponse
    {
        abort_unless($this->canManageAd($request, $ad), 403);

        $ad->update([
            'status' => Ad::STATUS_SOLD,
            'sold_at' => now(),
        ]);

        return redirect()
            ->route('ads.my')
            ->with('status', __('app.ad_marked_sold'));
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedPayload(Request $request, ?Ad $ad = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Ad::categories())],
            'price' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string', 'max:255'],
            'condition' => ['required', Rule::in(['new', 'used'])],
            'contact_phone' => ['required', 'string', 'max:40'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', Rule::in([Ad::STATUS_DRAFT, Ad::STATUS_PUBLISHED])],
        ]);

        $isPublishing = $validated['status'] === Ad::STATUS_PUBLISHED;
        $existingImages = $ad?->images ?? [];
        $incomingImages = collect($request->file('images', []))
            ->filter(fn (mixed $image): bool => $image instanceof UploadedFile);

        if (count($existingImages) + $incomingImages->count() > 6) {
            throw ValidationException::withMessages([
                'images' => __('app.image_limit_error'),
            ]);
        }

        $uploadedImages = $incomingImages
            ->map(fn (UploadedFile $image): string => $image->store('ads', 'public'))
            ->values()
            ->all();

        return [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'location' => $validated['location'],
            'condition' => $validated['condition'],
            'contact_phone' => $validated['contact_phone'],
            'images' => $uploadedImages === [] ? $existingImages : array_values([...$existingImages, ...$uploadedImages]),
            'status' => $validated['status'],
            'sold_at' => $isPublishing ? null : $ad?->sold_at,
        ];
    }

    protected function canManageAd(Request $request, Ad $ad): bool
    {
        return $ad->user_id === $request->user()->id
            || $request->user()->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN]);
    }

    /**
     * @return array{views:int, phone_reveals:int}
     */
    protected function analyticsFor(Ad $ad): array
    {
        return [
            'views' => $ad->interactions()
                ->where('type', AdInteraction::TYPE_VIEW)
                ->count(),
            'phone_reveals' => $ad->interactions()
                ->where('type', AdInteraction::TYPE_PHONE_REVEAL)
                ->count(),
        ];
    }

    protected function phoneRevealSessionKey(Ad $ad): string
    {
        return 'revealed_phone_ads.'.$ad->id;
    }
}
