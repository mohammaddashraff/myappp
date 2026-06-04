<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RiderWishlistController extends Controller
{
    use ResolvesRider;

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->status === 'active', 404);

        $rider = $this->riderFrom($request);

        $rider->wishlistItems()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        return back()->with('status', 'wishlist-item-saved');
    }

    public function destroy(Request $request, WishlistItem $wishlistItem): RedirectResponse
    {
        abort_unless($wishlistItem->rider()->is($this->riderFrom($request)), 404);

        $wishlistItem->delete();

        return back()->with('status', 'wishlist-item-removed');
    }
}
