<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderOrderController extends Controller
{
    use ResolvesRider;

    public function index(Request $request): View
    {
        $rider = $this->riderFrom($request);

        return view('riders.marketplace.orders.index', [
            'orders' => $rider->orders()
                ->with('items')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($this->riderFrom($request)->is($order->rider), 404);

        return view('riders.marketplace.orders.show', [
            'order' => $order->load('items.product'),
            'timeline' => [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_PREPARING, Order::STATUS_OUT_FOR_DELIVERY, Order::STATUS_DELIVERED],
        ]);
    }
}
