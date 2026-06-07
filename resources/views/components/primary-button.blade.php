<button {{ $attributes->merge(['type' => 'submit', 'class' => 'button-brand']) }}>
    {{ $slot }}
</button>
