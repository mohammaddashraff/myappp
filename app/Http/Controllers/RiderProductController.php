<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderProductController extends Controller
{
    public function accessories(Request $request): View
    {
        return $this->index($request, Product::TYPE_ACCESSORY, 'Accessories');
    }

    public function spareParts(Request $request): View
    {
        return $this->index($request, Product::TYPE_SPARE_PART, 'Spare Parts');
    }

    public function show(Request $request, Product $product): View
    {
        abort_unless($product->status === 'active', 404);

        return view('riders.marketplace.products.show', [
            'product' => $product,
            'canUseRiderActions' => $this->canUseRiderActions($request),
            'wishlistProductIds' => $this->wishlistProductIds($request),
            'relatedProducts' => Product::query()
                ->where('status', 'active')
                ->where('type', $product->type)
                ->whereKeyNot($product->id)
                ->latest()
                ->limit(3)
                ->get(),
        ]);
    }

    protected function index(Request $request, string $type, string $heading): View
    {
        $products = Product::query()
            ->where('status', 'active')
            ->where('type', $type)
            ->tap(fn (Builder $query) => $this->applyFilters($query, $request))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $filterOptions = Product::query()
            ->where('status', 'active')
            ->where('type', $type)
            ->get(['category', 'brand', 'location']);

        return view('riders.marketplace.products.index', [
            'products' => $products,
            'heading' => $heading,
            'productType' => $type,
            'categories' => $filterOptions->pluck('category')->unique()->sort()->values(),
            'brands' => $filterOptions->pluck('brand')->unique()->sort()->values(),
            'canUseRiderActions' => $this->canUseRiderActions($request),
            'locations' => $filterOptions->pluck('location')->unique()->sort()->values(),
            'wishlistProductIds' => $this->wishlistProductIds($request),
        ]);
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->when($request->filled('q'), function (Builder $query) use ($request): void {
            $term = '%'.$request->string('q')->toString().'%';
            $query->where(function (Builder $query) use ($term): void {
                $query->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        });

        foreach (['brand', 'category', 'location', 'condition'] as $field) {
            $query->when($request->filled($field), fn (Builder $query): Builder => $query->where($field, $request->string($field)->toString()));
        }

        $query->when($request->filled('min_price'), fn (Builder $query): Builder => $query->where('price', '>=', (float) $request->input('min_price')));
        $query->when($request->filled('max_price'), fn (Builder $query): Builder => $query->where('price', '<=', (float) $request->input('max_price')));
        $query->when($request->boolean('delivery_available'), fn (Builder $query): Builder => $query->where('delivery_available', true));
        $query->when($request->boolean('pickup_available'), fn (Builder $query): Builder => $query->where('pickup_available', true));
        $query->when($request->boolean('in_stock'), fn (Builder $query): Builder => $query->where('stock_quantity', '>', 0));

        $compatibilityFilters = [
            'motorcycle_type' => 'compatible_motorcycle_types',
            'motorcycle_brand' => 'compatible_motorcycle_brands',
            'motorcycle_model' => 'compatible_motorcycle_models',
        ];

        foreach ($compatibilityFilters as $requestKey => $column) {
            $query->when($request->filled($requestKey), function (Builder $query) use ($request, $requestKey, $column): void {
                $query->where($column, 'like', '%'.$request->string($requestKey)->toString().'%');
            });
        }
    }

    /**
     * @return array<int, int>
     */
    protected function wishlistProductIds(Request $request): array
    {
        return $request->user()?->rider?->wishlistItems()
            ->pluck('product_id')
            ->all() ?? [];
    }

    protected function canUseRiderActions(Request $request): bool
    {
        $user = $request->user();

        if ($user?->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN]) === true) {
            return false;
        }

        return $user?->rider !== null;
    }
}
