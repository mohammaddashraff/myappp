<x-guest-layout>
    <div class="mb-4 text-sm leading-6 text-slate-600">
        {{ __('rider.confirm_password_intro') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('rider.password')" />

            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 flex justify-end">
            <x-primary-button>
                {{ __('rider.confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
