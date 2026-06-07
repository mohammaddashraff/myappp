<button {{ $attributes->merge(['type' => 'button', 'class' => 'button-muted disabled:opacity-25']) }}>
    {{ $slot }}
</button>
