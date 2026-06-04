<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\DeliveryTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DeliveryTaskController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->deliveryPartnerProfile;
        $filter = $request->query('filter', 'available');

        $tasks = DeliveryTask::query()
            ->when($filter === 'available', fn ($query) => $query->whereNull('delivery_partner_profile_id')->pending())
            ->when($filter === 'active', fn ($query) => $query->whereBelongsTo($profile)->whereIn('status', [DeliveryTask::STATUS_ASSIGNED, DeliveryTask::STATUS_PICKED_UP, DeliveryTask::STATUS_OUT_FOR_DELIVERY]))
            ->when($filter === 'history', fn ($query) => $query->whereBelongsTo($profile)->whereIn('status', [DeliveryTask::STATUS_DELIVERED, DeliveryTask::STATUS_FAILED, DeliveryTask::STATUS_CANCELLED]))
            ->with('order')
            ->latest()
            ->paginate(15);

        return view('providers.delivery-partner.tasks.index', [
            'tasks' => $tasks,
            'filter' => $filter,
        ]);
    }

    public function accept(Request $request, DeliveryTask $deliveryTask): RedirectResponse
    {
        abort_unless($deliveryTask->delivery_partner_profile_id === null && $deliveryTask->status === DeliveryTask::STATUS_PENDING, 403);

        $deliveryTask->update([
            'delivery_partner_profile_id' => $request->user()->deliveryPartnerProfile->id,
            'status' => DeliveryTask::STATUS_ASSIGNED,
        ]);

        return back()->with('status', 'Delivery task accepted.');
    }

    public function update(Request $request, DeliveryTask $deliveryTask): RedirectResponse
    {
        abort_unless($deliveryTask->delivery_partner_profile_id === $request->user()->deliveryPartnerProfile?->id, 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(DeliveryTask::statuses())],
        ]);

        if (! $deliveryTask->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'This delivery task cannot move to that status.']);
        }

        $deliveryTask->update($validated);

        return back()->with('status', 'Delivery task updated.');
    }
}
