@if (session('status'))
    @php
        $statusMessage = match (session('status')) {
            'wishlist-item-saved' => 'Item saved to wishlist.',
            'wishlist-item-removed' => 'Item removed from wishlist.',
            default => session('status'),
        };
    @endphp

    <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
        {{ $statusMessage }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-800">
        <p>Please review the highlighted fields and try again.</p>
        <ul class="mt-2 list-inside list-disc font-semibold">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
