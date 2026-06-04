<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProviderApplication;
use App\Support\AccessRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderApplicationReviewController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.provider-applications.index', [
            'applications' => ProviderApplication::query()
                ->with(['user', 'reviewer'])
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
                ->when($request->filled('requested_role'), fn ($query) => $query->where('requested_role', $request->string('requested_role')))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'statuses' => AccessRoles::applicationStatuses(),
            'providerRoles' => AccessRoles::providerRoles(),
        ]);
    }

    public function show(ProviderApplication $providerApplication): View
    {
        return view('admin.provider-applications.show', [
            'application' => $providerApplication->load(['user', 'reviewer']),
        ]);
    }

    public function approve(Request $request, ProviderApplication $providerApplication): RedirectResponse
    {
        $this->authorizeApplicationReview($request, $providerApplication);

        $providerApplication->update([
            'status' => AccessRoles::STATUS_APPROVED,
            'admin_notes' => $request->string('admin_notes')->toString() ?: $providerApplication->admin_notes,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $providerApplication->user->assignRole($providerApplication->requested_role);
        $this->createOrApproveProfile($providerApplication);

        return back()->with('status', 'Application approved.');
    }

    public function reject(Request $request, ProviderApplication $providerApplication): RedirectResponse
    {
        $this->authorizeApplicationReview($request, $providerApplication);

        $providerApplication->update([
            'status' => AccessRoles::STATUS_REJECTED,
            'admin_notes' => $request->string('admin_notes')->toString() ?: $providerApplication->admin_notes,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Application rejected.');
    }

    public function suspend(Request $request, ProviderApplication $providerApplication): RedirectResponse
    {
        $this->authorizeApplicationReview($request, $providerApplication);

        $providerApplication->update([
            'status' => AccessRoles::STATUS_SUSPENDED,
            'admin_notes' => $request->string('admin_notes')->toString() ?: $providerApplication->admin_notes,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $this->updateProfileStatus($providerApplication, AccessRoles::STATUS_SUSPENDED);

        return back()->with('status', 'Provider suspended.');
    }

    public function activate(Request $request, ProviderApplication $providerApplication): RedirectResponse
    {
        $this->authorizeApplicationReview($request, $providerApplication);

        $providerApplication->update([
            'status' => AccessRoles::STATUS_APPROVED,
            'admin_notes' => $request->string('admin_notes')->toString() ?: $providerApplication->admin_notes,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $providerApplication->user->assignRole($providerApplication->requested_role);
        $this->createOrApproveProfile($providerApplication);

        return back()->with('status', 'Provider activated.');
    }

    protected function authorizeApplicationReview(Request $request, ProviderApplication $providerApplication): void
    {
        abort_if($providerApplication->user->hasRole(AccessRoles::SUPER_ADMIN) && ! $request->user()->hasRole(AccessRoles::SUPER_ADMIN), 403);
    }

    protected function createOrApproveProfile(ProviderApplication $application): void
    {
        match ($application->requested_role) {
            AccessRoles::SELLER => $application->user->sellerProfile()->updateOrCreate(
                ['user_id' => $application->user_id],
                [
                    'store_name' => $application->display_name ?: $application->business_name,
                    'seller_type' => 'mixed',
                    'phone' => $application->phone,
                    'address' => $application->address,
                    'city' => $application->city,
                    'description' => $application->description,
                    'status' => AccessRoles::STATUS_APPROVED,
                ],
            ),
            AccessRoles::SERVICE_CENTER => $application->user->serviceCenterProfile()->updateOrCreate(
                ['user_id' => $application->user_id],
                [
                    'center_name' => $application->display_name ?: $application->business_name,
                    'phone' => $application->phone,
                    'address' => $application->address,
                    'city' => $application->city,
                    'description' => $application->description,
                    'working_hours' => 'Not set',
                    'status' => AccessRoles::STATUS_APPROVED,
                ],
            ),
            AccessRoles::ROADSIDE_PROVIDER => $application->user->roadsideProviderProfile()->updateOrCreate(
                ['user_id' => $application->user_id],
                [
                    'provider_name' => $application->display_name ?: $application->business_name,
                    'phone' => $application->phone,
                    'address' => $application->address,
                    'city' => $application->city,
                    'coverage_area' => $application->city,
                    'status' => AccessRoles::STATUS_APPROVED,
                ],
            ),
            AccessRoles::DELIVERY_PARTNER => $application->user->deliveryPartnerProfile()->updateOrCreate(
                ['user_id' => $application->user_id],
                [
                    'full_name' => $application->display_name ?: $application->business_name,
                    'phone' => $application->phone,
                    'national_id' => null,
                    'license_number' => null,
                    'motorcycle_id' => null,
                    'status' => AccessRoles::STATUS_APPROVED,
                ],
            ),
            AccessRoles::DEALERSHIP => $application->user->dealershipProfile()->updateOrCreate(
                ['user_id' => $application->user_id],
                [
                    'dealership_name' => $application->display_name ?: $application->business_name,
                    'phone' => $application->phone,
                    'address' => $application->address,
                    'city' => $application->city,
                    'description' => $application->description,
                    'status' => AccessRoles::STATUS_APPROVED,
                ],
            ),
            default => null,
        };
    }

    protected function updateProfileStatus(ProviderApplication $application, string $status): void
    {
        match ($application->requested_role) {
            AccessRoles::SELLER => $application->user->sellerProfile?->update(['status' => $status]),
            AccessRoles::SERVICE_CENTER => $application->user->serviceCenterProfile?->update(['status' => $status]),
            AccessRoles::ROADSIDE_PROVIDER => $application->user->roadsideProviderProfile?->update(['status' => $status]),
            AccessRoles::DELIVERY_PARTNER => $application->user->deliveryPartnerProfile?->update(['status' => $status]),
            AccessRoles::DEALERSHIP => $application->user->dealershipProfile?->update(['status' => $status]),
            default => null,
        };
    }
}
