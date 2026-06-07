<section>
    <header>
        <h2 class="text-lg font-black text-slate-950">
            {{ __('app.account_contact_identity') }}
        </h2>

        <p class="mt-1 text-sm leading-6 text-slate-600">
            {{ __('app.update_account_email') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('app.name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('app.email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl" :value="old('email', $user->email)" required autocomplete="username" dir="ltr" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-slate-700">
                        {{ __('app.email_unverified') }}

                        <button form="send-verification" class="rounded-md text-sm font-bold text-teal-700 underline transition hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            {{ __('app.resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-bold text-teal-700">
                            {{ __('app.verification_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('app.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-teal-700"
                >{{ __('app.saved') }}</p>
            @endif
        </div>
    </form>
</section>
