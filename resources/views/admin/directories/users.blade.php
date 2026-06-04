@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black text-slate-950">Users</h1>
        <div class="mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left font-black text-slate-600"><tr><th class="p-4">Name</th><th class="p-4">Email</th><th class="p-4">Roles</th><th class="p-4">Joined</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr><td class="p-4 font-bold">{{ $user->name }}</td><td class="p-4">{{ $user->email }}</td><td class="p-4">{{ $user->roles->pluck('name')->map(fn ($role) => str($role)->headline())->join(', ') ?: 'No role' }}</td><td class="p-4">{{ $user->created_at->format('M d, Y') }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="p-8 text-center text-slate-500">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $users->links() }}</div>
    </div>
@endsection
