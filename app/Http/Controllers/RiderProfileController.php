<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRiderProfileRequest;
use App\Models\BatteryReplacementRequest;
use App\Models\DealerInquiry;
use App\Models\Order;
use App\Models\Rider;
use App\Models\RoadsideRequest;
use App\Models\ServiceBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RiderProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $rider = $request->user()->rider()
            ->with([
                'savedAddresses',
                'wishlistItems.product',
            ])
            ->first();

        return view('riders.profile.edit', [
            'user' => $request->user(),
            'rider' => $rider,
            'savedAddresses' => $rider?->savedAddresses()
                ->orderByDesc('is_default')
                ->latest()
                ->get() ?? collect(),
            'wishlistItems' => $rider?->wishlistItems()
                ->with('product')
                ->latest()
                ->get() ?? collect(),
            'historyItems' => $rider ? $this->historyItemsFor($rider) : collect(),
        ]);
    }

    public function update(UpdateRiderProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();
        $riderData = collect($validated)
            ->except('email')
            ->all();

        $user->rider()->updateOrCreate(
            ['user_id' => $user->id],
            [
                ...$riderData,
                'profile_completed_at' => now(),
            ],
        );

        $user->fill([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('rider.profile.edit')
            ->with('status', 'rider-profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'rider-password-updated');
    }

    /**
     * @return Collection<int, array{label: string, number: string, description: string, status: string, date: Carbon|null, url: string}>
     */
    protected function historyItemsFor(Rider $rider): Collection
    {
        return collect()
            ->merge($rider->orders()->with('items')->latest()->limit(5)->get()->map(fn (Order $order): array => [
                'label' => 'Order',
                'number' => $order->order_number,
                'description' => $order->items->count().' item order',
                'status' => $order->status,
                'date' => $order->created_at,
                'url' => route('rider.orders.show', $order),
            ]))
            ->merge($rider->serviceBookings()->with('service')->latest()->limit(5)->get()->map(fn (ServiceBooking $booking): array => [
                'label' => 'Service booking',
                'number' => $booking->booking_number,
                'description' => $booking->service->name,
                'status' => $booking->status,
                'date' => $booking->created_at,
                'url' => route('rider.bookings.show', $booking),
            ]))
            ->merge($rider->roadsideRequests()->latest()->limit(5)->get()->map(fn (RoadsideRequest $request): array => [
                'label' => 'Roadside request',
                'number' => $request->request_number,
                'description' => $request->assistance_type,
                'status' => $request->status,
                'date' => $request->created_at,
                'url' => route('rider.requests.show', ['roadside', $request->id]),
            ]))
            ->merge($rider->batteryReplacementRequests()->with('battery')->latest()->limit(5)->get()->map(fn (BatteryReplacementRequest $request): array => [
                'label' => 'Battery request',
                'number' => $request->request_number,
                'description' => $request->battery?->name ?? 'Battery replacement',
                'status' => $request->status,
                'date' => $request->created_at,
                'url' => route('rider.requests.show', ['battery', $request->id]),
            ]))
            ->merge($rider->dealerInquiries()->with(['dealer', 'motorcycle'])->latest()->limit(5)->get()->map(fn (DealerInquiry $inquiry): array => [
                'label' => 'Dealer inquiry',
                'number' => $inquiry->inquiry_number,
                'description' => $inquiry->dealer->name,
                'status' => $inquiry->status,
                'date' => $inquiry->created_at,
                'url' => route('rider.requests.show', ['dealer', $inquiry->id]),
            ]))
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }
}
