<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class SellerProductController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->sellerProfile;

        return view('providers.seller.products.index', [
            'products' => $profile->products()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('providers.seller.products.form', [
            'product' => new Product(['type' => Product::TYPE_ACCESSORY, 'status' => 'active']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $profile = $request->user()->sellerProfile;
        $validated = $this->validateProduct($request);

        $profile->products()->create([
            ...$validated,
            'image' => $this->imagePathFromRequest($request),
            'seller_name' => $profile->store_name,
            'location' => $profile->city,
            'delivery_available' => $request->boolean('delivery_available'),
            'pickup_available' => $request->boolean('pickup_available'),
            'installation_available' => $request->boolean('installation_available'),
        ]);

        return redirect()->route('seller.products.index')->with('status', 'Product created.');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeProduct($request, $product);

        return view('providers.seller.products.form', ['product' => $product]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);
        $profile = $request->user()->sellerProfile;
        $validated = $this->validateProduct($request);

        $product->update([
            ...$validated,
            'image' => $this->imagePathFromRequest($request, $product),
            'seller_name' => $profile->store_name,
            'location' => $profile->city,
            'delivery_available' => $request->boolean('delivery_available'),
            'pickup_available' => $request->boolean('pickup_available'),
            'installation_available' => $request->boolean('installation_available'),
        ]);

        return redirect()->route('seller.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request, $product);
        $this->deleteLocalImage($product->image);
        $product->delete();

        return redirect()->route('seller.products.index')->with('status', 'Product deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateProduct(Request $request): array
    {
        return collect($request->validate([
            'type' => ['required', 'in:accessory,spare_part,battery'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'category' => ['required', 'string', 'max:120'],
            'brand' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'in:new,used'],
            'image' => ['nullable', 'string', 'max:500'],
            'image_upload' => ['nullable', File::image()->max(5 * 1024)],
            'status' => ['required', Rule::in(Product::statuses())],
            'warranty_info' => ['nullable', 'string', 'max:255'],
            'return_policy' => ['nullable', 'string', 'max:255'],
        ]))->except('image_upload')->all();
    }

    protected function imagePathFromRequest(Request $request, ?Product $product = null): ?string
    {
        if ($request->hasFile('image_upload')) {
            $this->deleteLocalImage($product?->image);

            return $request->file('image_upload')->store('products', 'public');
        }

        return $request->input('image') ?: $product?->image;
    }

    protected function deleteLocalImage(?string $path): void
    {
        if ($path !== null && ! str($path)->startsWith(['http://', 'https://', '/'])) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function authorizeProduct(Request $request, Product $product): void
    {
        abort_unless($product->seller_profile_id === $request->user()->sellerProfile?->id, 403);
    }
}
