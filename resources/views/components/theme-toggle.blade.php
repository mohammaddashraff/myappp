<button
    type="button"
    x-data="{
        dark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.dark = ! this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            document.documentElement.style.colorScheme = this.dark ? 'dark' : 'light';
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        },
    }"
    x-on:click="toggle()"
    class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-950 shadow-sm transition hover:bg-yellow-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800"
    :aria-label="dark ? '{{ __('app.use_light_mode') }}' : '{{ __('app.use_dark_mode') }}'"
>
    <span x-cloak x-show="! dark" aria-hidden="true">{{ __('app.dark') }}</span>
    <span x-cloak x-show="dark" aria-hidden="true">{{ __('app.light') }}</span>
</button>
