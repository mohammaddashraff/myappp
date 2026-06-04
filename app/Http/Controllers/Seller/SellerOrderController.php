<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SellerOrderController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->sellerProfile;

        return view('providers.seller.orders.index', [
            'orders' => Order::query()
                ->whereHas('items.product', fn ($query) => $query->whereBelongsTo($profile))
                ->with(['items.product', 'rider'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $profile = $request->user()->sellerProfile;

        abort_unless($order->items()->whereHas('product', fn ($query) => $query->whereBelongsTo($profile))->exists(), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::statuses())],
        ]);

        if (! $order->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'This order cannot move to that status.']);
        }

        $order->update($validated);

        return back()->with('status', 'Order status updated.');
    }
}
