<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\BatteryReplacementRequest;
use App\Models\DealerInquiry;
use App\Models\RoadsideRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class RiderRequestController extends Controller
{
    use ResolvesRider;

    public function index(Request $request): View
    {
        $rider = $this->riderFrom($request);

        $requests = collect()
            ->merge($rider->roadsideRequests()->latest()->get()->map(fn (RoadsideRequest $request): array => [
                'id' => $request->id,
                'type' => 'roadside',
                'number' => $request->request_number,
                'label' => 'Roadside Assistance',
                'description' => $request->assistance_type.' - '.str($request->location)->limit(80),
                'status' => $request->status,
                'created_at' => $request->created_at,
            ]))
            ->merge($rider->batteryReplacementRequests()->with('battery')->latest()->get()->map(fn (BatteryReplacementRequest $request): array => [
                'id' => $request->id,
                'type' => 'battery',
                'number' => $request->request_number,
                'label' => 'Battery Replacement',
                'description' => ($request->battery?->name ?? 'Battery').' - '.str($request->location)->limit(80),
                'status' => $request->status,
                'created_at' => $request->created_at,
            ]))
            ->merge($rider->dealerInquiries()->with(['dealer', 'motorcycle'])->latest()->get()->map(fn (DealerInquiry $inquiry): array => [
                'id' => $inquiry->id,
                'type' => 'dealer',
                'number' => $inquiry->inquiry_number,
                'label' => 'Dealer Inquiry',
                'description' => $inquiry->dealer->name.' - '.($inquiry->motorcycle?->fullName() ?? 'General inquiry'),
                'status' => $inquiry->status,
                'created_at' => $inquiry->created_at,
            ]))
            ->sortByDesc('created_at')
            ->values();

        return view('riders.marketplace.requests.index', [
            'requests' => $requests,
        ]);
    }

    public function show(Request $request, string $type, int $id): View
    {
        $rider = $this->riderFrom($request);

        [$record, $timeline] = match ($type) {
            'roadside' => [
                $rider->roadsideRequests()->with('motorcycle')->findOrFail($id),
                RoadsideRequest::statuses(),
            ],
            'battery' => [
                $rider->batteryReplacementRequests()->with(['battery', 'motorcycle'])->findOrFail($id),
                BatteryReplacementRequest::statuses(),
            ],
            'dealer' => [
                $rider->dealerInquiries()->with(['dealer', 'motorcycle'])->findOrFail($id),
                DealerInquiry::statuses(),
            ],
            default => abort(404),
        };

        return view('riders.marketplace.requests.show', [
            'type' => $type,
            'record' => $record,
            'timeline' => Collection::make($timeline),
        ]);
    }
}
