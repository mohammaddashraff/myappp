<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\Dealer;
use App\Models\DealerInquiry;
use App\Models\DealerMotorcycle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RiderDealerController extends Controller
{
    use ResolvesRider;

    public function index(Request $request): View
    {
        $dealers = Dealer::query()
            ->where('status', 'active')
            ->withCount(['motorcycles' => fn (Builder $query): Builder => $query->where('status', 'active')])
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where(function (Builder $query) use ($term): void {
                    $query->where('name', 'like', $term)
                        ->orWhere('location', 'like', $term);
                });
            })
            ->latest()
            ->get();

        $motorcycles = DealerMotorcycle::query()
            ->where('status', 'active')
            ->with('dealer')
            ->when($request->filled('brand'), fn (Builder $query): Builder => $query->where('brand', $request->string('brand')->toString()))
            ->when($request->filled('condition'), fn (Builder $query): Builder => $query->where('condition', $request->string('condition')->toString()))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('riders.marketplace.dealers.index', [
            'dealers' => $dealers,
            'motorcycles' => $motorcycles,
            'brands' => DealerMotorcycle::query()->where('status', 'active')->pluck('brand')->unique()->sort()->values(),
        ]);
    }

    public function show(Dealer $dealer): View
    {
        abort_unless($dealer->status === 'active', 404);

        return view('riders.marketplace.dealers.show', [
            'dealer' => $dealer->load(['motorcycles' => fn ($query) => $query->where('status', 'active')->latest()]),
        ]);
    }

    public function motorcycle(DealerMotorcycle $dealerMotorcycle): View
    {
        abort_unless($dealerMotorcycle->status === 'active', 404);

        return view('riders.marketplace.dealers.motorcycle', [
            'motorcycle' => $dealerMotorcycle->load('dealer'),
        ]);
    }

    public function createInquiry(Request $request, Dealer $dealer, ?DealerMotorcycle $dealerMotorcycle = null): View
    {
        abort_unless($dealer->status === 'active', 404);

        if ($dealerMotorcycle !== null) {
            abort_unless($dealerMotorcycle->dealer_id === $dealer->id && $dealerMotorcycle->status === 'active', 404);
        }

        $rider = $this->riderFrom($request);

        return view('riders.marketplace.dealers.inquiry', [
            'dealer' => $dealer,
            'motorcycle' => $dealerMotorcycle,
            'rider' => $rider,
        ]);
    }

    public function storeInquiry(Request $request, Dealer $dealer, ?DealerMotorcycle $dealerMotorcycle = null): RedirectResponse
    {
        abort_unless($dealer->status === 'active', 404);

        if ($dealerMotorcycle !== null) {
            abort_unless($dealerMotorcycle->dealer_id === $dealer->id && $dealerMotorcycle->status === 'active', 404);
        }

        $rider = $this->riderFrom($request);

        $validated = $request->validate([
            'rider_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:1000'],
            'preferred_contact_method' => ['required', Rule::in(['phone', 'whatsapp', 'email'])],
        ]);

        $inquiry = $rider->dealerInquiries()->create([
            'dealer_id' => $dealer->id,
            'dealer_motorcycle_id' => $dealerMotorcycle?->id,
            'inquiry_number' => DealerInquiry::nextNumber(),
            'rider_name' => $validated['rider_name'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'preferred_contact_method' => $validated['preferred_contact_method'],
            'status' => DealerInquiry::STATUS_PENDING,
        ]);

        return redirect()
            ->route('rider.requests.show', ['type' => 'dealer', 'id' => $inquiry->id])
            ->with('status', 'Dealer inquiry sent.');
    }
}
