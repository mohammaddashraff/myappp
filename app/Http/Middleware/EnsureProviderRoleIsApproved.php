<?php

namespace App\Http\Middleware;

use App\Support\AccessRoles;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProviderRoleIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user !== null && $user->hasRole($role), 403);

        if (in_array($role, [AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN, AccessRoles::RIDER], true)) {
            return $next($request);
        }

        $profile = match ($role) {
            AccessRoles::SELLER => $user->sellerProfile,
            AccessRoles::SERVICE_CENTER => $user->serviceCenterProfile,
            AccessRoles::ROADSIDE_PROVIDER => $user->roadsideProviderProfile,
            AccessRoles::DELIVERY_PARTNER => $user->deliveryPartnerProfile,
            AccessRoles::DEALERSHIP => $user->dealershipProfile,
            default => null,
        };

        if ($profile?->status !== AccessRoles::STATUS_APPROVED) {
            return redirect()
                ->route('rider.dashboard')
                ->with('status', 'Your provider account is not active yet.');
        }

        return $next($request);
    }
}
