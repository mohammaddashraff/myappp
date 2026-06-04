@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ $product->exists ? route('seller.products.update', $product) : route('seller.products.store') }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @if ($product->exists) @method('PUT') @endif
            <p class="text-sm font-bold uppercase text-teal-700">Seller product</p>
            <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $product->exists ? 'Edit product' : 'Add product' }}</h1>
            @if ($errors->any()) <div class="mt-5 rounded-lg bg-rose-50 p-4 text-sm font-bold text-rose-800">Please review the form.</div> @endif
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-bold">Type<select name="type" class="rounded-md border-slate-300"><option value="accessory" @selected(old('type', $product->type) === 'accessory')>Accessory</option><option value="spare_part" @selected(old('type', $product->type) === 'spare_part')>Spare part</option><option value="battery" @selected(old('type', $product->type) === 'battery')>Battery</option></select></label>
                <label class="grid gap-2 text-sm font-bold">Name<input name="name" value="{{ old('name', $product->name) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Category<input name="category" value="{{ old('category', $product->category) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Brand<input name="brand" value="{{ old('brand', $product->brand) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Price<input name="price" type="number" step="0.01" value="{{ old('price', $product->price) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Stock<input name="stock_quantity" type="number" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Condition<select name="condition" class="rounded-md border-slate-300"><option value="new" @selected(old('condition', $product->condition) === 'new')>New</option><option value="used" @selected(old('condition', $product->condition) === 'used')>Used</option></select></label>
                <label class="grid gap-2 text-sm font-bold">Status<select name="status" class="rounded-md border-slate-300">@foreach (\App\Models\Product::statuses() as $status)<option value="{{ $status }}" @selected(old('status', $product->status) === $status)>{{ str($status)->headline() }}</option>@endforeach</select></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Image URL<input name="image" value="{{ old('image', str($product->image)->startsWith(['http://', 'https://', '/']) ? $product->image : '') }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">
                    Product image upload
                    <input name="image_upload" type="file" accept="image/*" class="rounded-md border border-slate-300 bg-white px-3 py-2">
                    <span class="text-xs font-semibold text-slate-500">JPG, PNG, GIF, or WebP up to 5 MB.</span>
                    @if ($product->imageUrl())
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="mt-2 aspect-[4/3] w-40 rounded-md object-cover">
                    @endif
                </label>
                <label class="grid gap-2 text-sm font-bold">Warranty<input name="warranty_info" value="{{ old('warranty_info', $product->warranty_info) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Return policy<input name="return_policy" value="{{ old('return_policy', $product->return_policy) }}" class="rounded-md border-slate-300"></label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="delivery_available" @checked(old('delivery_available', $product->delivery_available))> Delivery available</label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="pickup_available" @checked(old('pickup_available', $product->pickup_available))> Pickup available</label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="installation_available" @checked(old('installation_available', $product->installation_available))> Installation available</label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Description<textarea name="description" rows="5" class="rounded-md border-slate-300" required>{{ old('description', $product->description) }}</textarea></label>
            </div>
            <button class="mt-6 rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Save product</button>
        </form>
    </div>
@endsection
