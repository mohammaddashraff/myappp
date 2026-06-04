<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderCartController extends Controller
{
    use ResolvesRider;

    public function index(Request $request): View
    {
        $rider = $this->riderFrom($request);

        $cartItems = $rider->cartItems()
            ->with('product')
            ->latest()
            ->get();

        return view('riders.marketplace.cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $cartItems->sum(fn (CartItem $cartItem): float => $cartItem->total()),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $rider = $this->riderFrom($request);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        if (! $product->isInStock()) {
            return back()->withErrors(['product' => 'Product is out of stock.']);
        }

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cartItem = $rider->cartItems()->firstOrNew(['product_id' => $product->id]);
        $newQuantity = $cartItem->exists ? $cartItem->quantity + $quantity : $quantity;

        if ($newQuantity > $product->stock_quantity) {
            return back()->withErrors(['quantity' => 'Quantity cannot exceed available stock.']);
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return redirect()
            ->route($request->boolean('checkout') ? 'rider.checkout.show' : 'rider.cart.index')
            ->with('status', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->ownedCartItem($request, $cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->load('product');

        if ((int) $validated['quantity'] > $cartItem->product->stock_quantity) {
            return back()->withErrors(['quantity' => 'Quantity cannot exceed available stock.']);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('status', 'Cart updated.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->ownedCartItem($request, $cartItem)->delete();

        return back()->with('status', 'Item removed from cart.');
    }

    protected function ownedCartItem(Request $request, CartItem $cartItem): CartItem
    {
        abort_unless($this->riderFrom($request)->is($cartItem->rider), 404);

        return $cartItem;
    }
}
