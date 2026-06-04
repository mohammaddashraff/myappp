@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase text-teal-700">{{ str($application->requested_role)->headline() }}</p>
            <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $application->business_name }}</h1>
            <p class="mt-3 text-sm text-slate-600">{{ $application->description ?: 'No description provided.' }}</p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @foreach ([
                    'Applicant' => $application->user->name.' · '.$application->user->email,
                    'Phone' => $application->phone,
                    'City' => $application->city,
                    'Address' => $application->address,
                    'Status' => str($application->status)->headline(),
                    'Reviewed by' => $application->reviewer?->name ?? 'Not reviewed',
                ] as $label => $value)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase text-slate-500">{{ $label }}</p>
                        <p class="mt-1 text-sm font-bold text-slate-950">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 grid gap-3 md:grid-cols-2">
                @foreach ([
                    'approve' => ['label' => 'Approve', 'class' => 'bg-teal-600 text-white'],
                    'reject' => ['label' => 'Reject', 'class' => 'border border-rose-200 text-rose-700'],
                    'suspend' => ['label' => 'Suspend', 'class' => 'border border-amber-200 text-amber-700'],
                    'activate' => ['label' => 'Activate', 'class' => 'border border-slate-200 text-slate-700'],
                ] as $action => $button)
                    <form method="POST" action="{{ route('admin.provider-applications.'.$action, $application) }}" class="rounded-lg border border-slate-200 p-4">
                        @csrf
                        @method('PATCH')
                        <label class="grid gap-2 text-sm font-bold text-slate-700">
                            Admin notes
                            <textarea name="admin_notes" rows="2" class="rounded-md border-slate-300 text-sm">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                        </label>
                        <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md px-4 py-2.5 text-sm font-black {{ $button['class'] }}">
                            {{ $button['label'] }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection
