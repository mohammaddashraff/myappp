<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function show(Request $request): View
    {
        return view('subscriptions.show', [
            'subscription' => $request->user()->subscription()->first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', Rule::in(Subscription::plans())],
        ]);

        $subscription = $request->user()->subscription()->first();

        if ($subscription?->isActive() && $subscription->plan === $validated['plan']) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.subscription_already_active'));
        }

        if ($subscription?->isActive() && $subscription->plan === Subscription::PLAN_BUSINESS) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.subscription_already_best_plan'));
        }

        if ($subscription?->isActive()) {
            $request->session()->put('subscription_upgrade_plan', $validated['plan']);

            return redirect()
                ->route('subscriptions.checkout')
                ->with('status', __('app.subscription_upgrade_selected'));
        }

        $request->user()->subscription()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'plan' => $validated['plan'],
                'status' => Subscription::STATUS_INACTIVE,
                'starts_at' => null,
                'ends_at' => null,
                'activated_at' => null,
                'activated_by' => null,
                'payment_gateway' => null,
                'payment_reference' => null,
            ],
        );

        return redirect()
            ->route('subscriptions.checkout')
            ->with('status', __('app.subscription_plan_selected'));
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $subscription = $request->user()->subscription()->first();
        $upgradePlan = $request->session()->get('subscription_upgrade_plan');

        if ($subscription === null) {
            return redirect()->route('subscriptions.show');
        }

        if ($subscription->isActive() && $upgradePlan === null) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.subscription_already_active'));
        }

        if ($subscription->isActive()) {
            $subscription->plan = $upgradePlan;
        }

        return view('subscriptions.checkout', [
            'subscription' => $subscription,
            'isUpgrade' => $upgradePlan !== null,
        ]);
    }

    public function pay(Request $request): RedirectResponse
    {
        $subscription = $request->user()->subscription()->first();
        $upgradePlan = $request->session()->get('subscription_upgrade_plan');

        abort_unless($subscription !== null, 404);

        if ($subscription->isActive() && $upgradePlan === null) {
            return redirect()
                ->route('subscriptions.show')
                ->with('status', __('app.subscription_already_active'));
        }

        $request->validate([
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'digits:16'],
            'expiry_month' => ['required', 'digits:2'],
            'expiry_year' => ['required', 'digits:2'],
            'cvv' => ['required', 'digits:3'],
        ]);

        $subscription->update([
            'plan' => $upgradePlan ?? $subscription->plan,
            'status' => Subscription::STATUS_ACTIVE,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'activated_at' => now(),
            'activated_by' => null,
            'payment_gateway' => 'test_gateway',
            'payment_reference' => 'TEST-'.str()->upper(str()->random(10)),
        ]);

        $request->session()->forget('subscription_upgrade_plan');

        return redirect()
            ->route('subscriptions.show')
            ->with('status', $upgradePlan === null
                ? __('app.subscription_payment_success')
                : __('app.subscription_upgrade_success'));
    }
}
