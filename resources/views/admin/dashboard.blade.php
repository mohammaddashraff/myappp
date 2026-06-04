@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase text-teal-700">Admin</p>
            <h1 class="mt-2 text-3xl font-black text-slate-950">Platform dashboard</h1>
            <p class="mt-3 text-sm text-slate-600">Review platform activity and provider applications.</p>
        </div>

        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                'Total users' => $totalUsers,
                'Total riders' => $totalRiders,
                'Pending applications' => $pendingApplications,
                'Approved providers' => $approvedProvidersCount,
                'Suspended providers' => $suspendedProvidersCount,
                'Sellers' => $sellersCount,
                'Service centers' => $serviceCentersCount,
                'Roadside providers' => $roadsideProvidersCount,
                'Delivery partners' => $deliveryPartnersCount,
                'Dealerships' => $dealershipsCount,
                'Products' => $totalProducts,
                'Orders' => $totalOrders,
                'Services' => $totalServices,
                'Service bookings' => $totalServiceBookings,
                'Roadside requests' => $totalRoadsideRequests,
                'Battery requests' => $totalBatteryRequests,
                'Dealer inquiries' => $totalDealerInquiries,
            ] as $label => $value)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-bold text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-3xl font-black text-slate-950">{{ number_format($value) }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('admin.provider-applications.index') }}" class="inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Review applications</a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Users</a>
            <a href="{{ route('admin.sellers.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Sellers</a>
            <a href="{{ route('admin.service-centers.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Service centers</a>
            <a href="{{ route('admin.roadside-providers.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Roadside providers</a>
            <a href="{{ route('admin.delivery-partners.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Delivery partners</a>
            <a href="{{ route('admin.dealerships.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Dealerships</a>
        </div>

        <div class="mt-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-black text-slate-950">Recent applications</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($recentApplications as $application)
                    <a href="{{ route('admin.provider-applications.show', $application) }}" class="flex items-center justify-between gap-4 py-3 text-sm">
                        <span class="font-bold text-slate-950">{{ $application->business_name }}</span>
                        <span class="text-slate-500">{{ str($application->requested_role)->headline() }} · {{ str($application->status)->headline() }}</span>
                    </a>
                @empty
                    <p class="py-4 text-sm font-bold text-slate-500">No applications yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
