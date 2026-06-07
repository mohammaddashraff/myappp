<x-guest-layout>
    <div class="mb-4 text-sm leading-6 text-slate-600">
        {{ __('app.verify_email_intro') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-bold text-teal-700">
            {{ __('app.verification_link_sent') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('app.resend_verification_link') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="rounded-md text-sm font-bold text-slate-600 underline transition hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                {{ __('app.log_out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
