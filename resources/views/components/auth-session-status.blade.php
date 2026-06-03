@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm font-medium text-teal-700']) }}>
        {{ $status }}
    </div>
@endif
