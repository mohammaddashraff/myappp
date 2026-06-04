<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RiderCheckoutController extends Controller
{
    use ResolvesRider;

    public const DELIVERY_FEE = 75;

    public function show(Request $request): View|RedirectResponse
    {
        $rider = $this->riderFrom($request);
        $cartItems = $rider->cartItems()->with('product')->latest()->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('rider.cart.index')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        return view('riders.marketplace.checkout.show', [
            'cartItems' => $cartItems,
            'subtotal' => $cartItems->sum(fn (CartItem $cartItem): float => $cartItem->total()),
            'deliveryFee' => self::DELIVERY_FEE,
            'profileAddress' => $rider->current_address,
            'savedAddresses' => $rider->savedAddresses()
                ->orderByDesc('is_default')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rider = $this->riderFrom($request);

        $validated = $request->validate([
            'delivery_method' => ['required', Rule::in(['delivery', 'pickup'])],
            'payment_method' => ['required', Rule::in(['cash_on_delivery', 'pay_at_pickup'])],
            'address_choice' => ['nullable', Rule::in(['saved', 'profile', 'new'])],
            'saved_address_id' => ['nullable', 'integer'],
            'address_city' => ['nullable', 'string', 'max:120'],
            'address_area' => ['nullable', 'string', 'max:120'],
            'address_street' => ['nullable', 'string', 'max:180'],
            'address_building' => ['nullable', 'string', 'max:60'],
            'address_floor' => ['nullable', 'string', 'max:60'],
            'address_apartment' => ['nullable', 'string', 'max:60'],
            'address_landmark' => ['nullable', 'string', 'max:180'],
            'address_notes' => ['nullable', 'string', 'max:255'],
        ], [
            'delivery_method.required' => 'Delivery method is required.',
            'payment_method.required' => 'Payment method is required.',
        ]);

        $addressChoice = $validated['address_choice'] ?? ($rider->savedAddresses()->exists() || $rider->current_address ? 'saved' : 'new');
        $address = null;

        if ($validated['delivery_method'] === 'delivery' && $addressChoice === 'saved') {
            $defaultSavedAddress = $rider->savedAddresses()
                ->orderByDesc('is_default')
                ->latest()
                ->first();
            $savedAddressId = $validated['saved_address_id'] ?? $defaultSavedAddress?->id;

            if ($savedAddressId !== null) {
                $address = $rider->savedAddresses()
                    ->whereKey($savedAddressId)
                    ->firstOrFail()
                    ->formattedAddress();
            } else {
                $address = $rider->current_address;
            }
        }

        if ($validated['delivery_method'] === 'delivery' && $addressChoice === 'profile') {
            $address = $rider->current_address;
        }

        if ($validated['delivery_method'] === 'delivery' && $addressChoice === 'new') {
            $requiredAddressFields = [
                'address_city' => 'City is required for delivery.',
                'address_area' => 'Area / district is required for delivery.',
                'address_street' => 'Street name is required for delivery.',
                'address_building' => 'Building number is required for delivery.',
                'address_floor' => 'Floor is required for delivery.',
                'address_apartment' => 'Apartment number is required for delivery.',
            ];
            $addressErrors = [];

            foreach ($requiredAddressFields as $field => $message) {
                if (blank($validated[$field] ?? null)) {
                    $addressErrors[$field] = $message;
                }
            }

            if ($addressErrors !== []) {
                return back()
                    ->withErrors($addressErrors)
                    ->withInput();
            }

            $address = $this->formatDeliveryAddress($validated);
        }

        if ($validated['delivery_method'] === 'delivery' && blank($address)) {
            return back()
                ->withErrors(['address_city' => 'Address is required for delivery.'])
                ->withInput();
        }

        $cartItems = $rider->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('rider.cart.index')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        foreach ($cartItems as $cartItem) {
            if (! $cartItem->product->isInStock()) {
                return redirect()
                    ->route('rider.cart.index')
                    ->withErrors(['product' => 'Product is out of stock.']);
            }

            if ($cartItem->quantity > $cartItem->product->stock_quantity) {
                return redirect()
                    ->route('rider.cart.index')
                    ->withErrors(['quantity' => 'Quantity cannot exceed available stock.']);
            }
        }

        $order = DB::transaction(function () use ($rider, $cartItems, $validated, $address): Order {
            $subtotal = $cartItems->sum(fn (CartItem $cartItem): float => $cartItem->total());
            $deliveryFee = $validated['delivery_method'] === 'delivery' ? self::DELIVERY_FEE : 0;
            $order = $rider->orders()->create([
                'order_number' => Order::nextNumber(),
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $subtotal + $deliveryFee,
                'delivery_method' => $validated['delivery_method'],
                'payment_method' => $validated['payment_method'],
                'address' => $validated['delivery_method'] === 'delivery' ? $address : null,
                'status' => Order::STATUS_PENDING,
            ]);

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product()->lockForUpdate()->firstOrFail();

                if ($cartItem->quantity > $product->stock_quantity) {
                    abort(422, 'Quantity cannot exceed available stock.');
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_type' => $product->type,
                    'product_price' => $product->price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => (float) $product->price * $cartItem->quantity,
                ]);

                $product->decrement('stock_quantity', $cartItem->quantity);
                $cartItem->delete();
            }

            return $order;
        });

        return redirect()
            ->route('rider.orders.show', $order)
            ->with('status', 'Order confirmed.');
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    protected function formatDeliveryAddress(array $validated): string
    {
        $parts = [
            'Apartment '.$validated['address_apartment'],
            'Floor '.$validated['address_floor'],
            'Building '.$validated['address_building'],
            $validated['address_street'],
            $validated['address_area'],
            $validated['address_city'],
        ];

        if (filled($validated['address_landmark'] ?? null)) {
            $parts[] = 'Landmark: '.$validated['address_landmark'];
        }

        if (filled($validated['address_notes'] ?? null)) {
            $parts[] = 'Notes: '.$validated['address_notes'];
        }

        return collect($parts)
            ->filter(fn (?string $part): bool => filled($part))
            ->join(', ');
    }
}
