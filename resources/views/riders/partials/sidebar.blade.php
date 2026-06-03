@props([
    'active' => 'garage',
    'showAddButton' => true,
])

<aside class="w-full rounded-lg border border-slate-200 bg-white p-4 text-slate-950 shadow-sm lg:sticky lg:top-6 lg:self-start">
    <div class="border-b border-slate-200 pb-4">
        <a href="{{ route('rider.dashboard') }}" class="inline-flex items-center gap-3">
            <span class="flex size-11 items-center justify-center rounded-lg bg-yellow-300 text-lg font-black text-slate-950 shadow-sm">ط</span>
            <span>
                <span class="block text-lg font-extrabold text-slate-950">{{ __('rider.brand') }}</span>
                <span class="block text-sm text-slate-500">{{ __('rider.brand_subtitle') }}</span>
            </span>
        </a>
    </div>

    <div class="mt-6">
        <p class="px-3 text-xs font-bold uppercase text-slate-400">Workspace</p>
    </div>

    <nav class="mt-3 space-y-1">
        <a href="{{ route('rider.dashboard') }}" class="flex items-center gap-3 rounded-lg border px-3 py-2.5 text-sm font-bold transition {{ $active === 'dashboard' ? 'border-teal-200 bg-teal-50 text-teal-950' : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-slate-950' }}">
            <span class="size-2 rounded-full {{ $active === 'dashboard' ? 'bg-teal-500' : 'bg-slate-300' }}"></span>
            <span>{{ __('rider.rider_dashboard') }}</span>
        </a>
        <a href="{{ route('rider.garage') }}" class="flex items-center gap-3 rounded-lg border px-3 py-2.5 text-sm font-bold transition {{ $active === 'garage' ? 'border-teal-200 bg-teal-50 text-teal-950' : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-slate-950' }}">
            <span class="size-2 rounded-full {{ $active === 'garage' ? 'bg-teal-500' : 'bg-slate-300' }}"></span>
            <span>{{ __('rider.garage') }}</span>
        </a>
        <a href="{{ route('rider.profile.edit') }}" class="flex items-center gap-3 rounded-lg border px-3 py-2.5 text-sm font-bold transition {{ $active === 'profile' ? 'border-yellow-200 bg-yellow-50 text-slate-950' : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-slate-950' }}">
            <span class="size-2 rounded-full {{ $active === 'profile' ? 'bg-yellow-400' : 'bg-slate-300' }}"></span>
            <span>{{ __('rider.edit_profile') }}</span>
        </a>
    </nav>

    @if ($showAddButton)
        <div class="mt-6">
            <a href="{{ route('rider.motorcycles.create') }}" class="inline-flex w-full items-center justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-slate-800">
                {{ __('rider.add_motorcycle') }}
            </a>
        </div>
    @endif

    <div class="mt-6 border-t border-slate-200 pt-5">
        <p class="mb-3 px-1 text-xs font-bold uppercase text-slate-400">Settings</p>
        <div class="flex items-center gap-2">
            <div class="shrink-0">
                <x-language-switcher variant="dark" />
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="inline-flex w-full justify-center rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">
                    {{ __('rider.logout') }}
                </button>
            </form>
        </div>
    </div>
</aside>
