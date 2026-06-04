@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase text-teal-700">Admin</p>
            <h1 class="mt-2 text-3xl font-black text-slate-950">Provider applications</h1>
        </div>

        <form method="GET" class="mt-5 grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-3">
            <select name="status" class="rounded-md border-slate-300 text-sm">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->headline() }}</option>
                @endforeach
            </select>
            <select name="requested_role" class="rounded-md border-slate-300 text-sm">
                <option value="">All roles</option>
                @foreach ($providerRoles as $role)
                    <option value="{{ $role }}" @selected(request('requested_role') === $role)>{{ str($role)->headline() }}</option>
                @endforeach
            </select>
            <button class="rounded-md bg-slate-950 px-4 py-2 text-sm font-black text-white">Filter</button>
        </form>

        <div class="mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-black uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Applicant</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">City</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($applications as $application)
                        <tr>
                            <td class="px-4 py-3 font-black text-slate-950">{{ $application->business_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $application->user->email }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ str($application->requested_role)->headline() }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $application->phone }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $application->city }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">{{ str($application->status)->headline() }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.provider-applications.show', $application) }}" class="font-black text-teal-700">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-500">No provider applications yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $applications->links() }}</div>
    </div>
@endsection
