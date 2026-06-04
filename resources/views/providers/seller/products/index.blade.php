@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Seller</p>
                <h1 class="text-3xl font-black text-slate-950">My products</h1>
            </div>
            <a href="{{ route('seller.products.create') }}" class="rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Add product</a>
        </div>
        @include('riders.marketplace.partials.flash')
        <div class="mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-black text-slate-600">
                    <tr><th class="p-4">Product</th><th class="p-4">Type</th><th class="p-4">Price</th><th class="p-4">Stock</th><th class="p-4">Status</th><th class="p-4">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $product)
                        <tr>
                            <td class="p-4 font-bold text-slate-950">{{ $product->name }}</td>
                            <td class="p-4">{{ $product->typeLabel() }}</td>
                            <td class="p-4">EGP {{ number_format((float) $product->price) }}</td>
                            <td class="p-4">{{ $product->stock_quantity }}</td>
                            <td class="p-4">{{ str($product->status)->headline() }}</td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('seller.products.edit', $product) }}" class="rounded-md border border-slate-300 px-3 py-2 font-bold">Edit</a>
                                    <form method="POST" action="{{ route('seller.products.destroy', $product) }}">
                                        @csrf @method('DELETE')
                                        <button class="rounded-md border border-rose-200 px-3 py-2 font-bold text-rose-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center font-bold text-slate-500">No products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $products->links() }}</div>
    </div>
@endsection
