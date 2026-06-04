@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black text-slate-950">{{ $title }}</h1>
        <div class="mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-black text-slate-600"><tr><th class="p-4">Name</th><th class="p-4">Owner</th><th class="p-4">Phone</th><th class="p-4">City</th><th class="p-4">Status</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($profiles as $profile)
                        <tr><td class="p-4 font-bold">{{ $profile->{$nameField} }}</td><td class="p-4">{{ $profile->user?->email }}</td><td class="p-4">{{ $profile->phone }}</td><td class="p-4">{{ $profile->city ?? 'N/A' }}</td><td class="p-4">{{ str($profile->status)->headline() }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-slate-500">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $profiles->links() }}</div>
    </div>
@endsection
