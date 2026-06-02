<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverSignupRequest;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class DriverSignupController extends Controller
{
    private const DRAFT_SESSION_KEY = 'drivers.signup.draft';

    /**
     * @var array<string, array{number: int, title: string, subtitle: string}>
     */
    private const STEPS = [
        'identity' => [
            'number' => 1,
            'title' => 'Legal identity',
            'subtitle' => 'Name, date of birth, and address',
        ],
        'contact' => [
            'number' => 2,
            'title' => 'Contact',
            'subtitle' => 'Phone numbers and emergency contact',
        ],
        'documents' => [
            'number' => 3,
            'title' => 'Documents',
            'subtitle' => 'ID, selfie, licenses, and checks',
        ],
        'vehicle' => [
            'number' => 4,
            'title' => 'Motorcycle',
            'subtitle' => 'Plate, ownership, and equipment',
        ],
        'review' => [
            'number' => 5,
            'title' => 'Review',
            'subtitle' => 'Consent and submit',
        ],
    ];

    public function create(Request $request): RedirectResponse
    {
        if ($request->boolean('fresh')) {
            $request->session()->forget(self::DRAFT_SESSION_KEY);
        }

        $this->draft($request);

        return redirect()->route('drivers.signup.step', 'identity');
    }

    public function show(Request $request, string $step): View
    {
        $this->ensureValidStep($step);

        return view('drivers.signup', [
            'draft' => $this->draft($request),
            'step' => $step,
            'steps' => self::STEPS,
        ]);
    }

    public function store(StoreDriverSignupRequest $request): RedirectResponse
    {
        $step = (string) $request->route('step');
        $this->ensureValidStep($step);

        if ($step === 'review') {
            return $this->submitApplication($request);
        }

        $draft = $this->draft($request);
        $draft['fields'] = [
            ...($draft['fields'] ?? []),
            ...Arr::except($request->validated(), array_keys(Driver::PHOTO_FIELDS)),
        ];

        foreach (Driver::PHOTO_FIELDS as $inputName => $columnName) {
            if ($request->hasFile($inputName)) {
                $this->deleteDraftPhoto($draft['photos'][$inputName] ?? null);
                $draft['photos'][$inputName] = $this->storeDraftPhoto(
                    $draft['id'],
                    $inputName,
                    $request->file($inputName),
                );
            }
        }

        $request->session()->put(self::DRAFT_SESSION_KEY, $draft);

        return redirect()->route('drivers.signup.step', $this->nextStep($step));
    }

    public function success(): View
    {
        return view('drivers.success');
    }

    private function submitApplication(StoreDriverSignupRequest $request): RedirectResponse
    {
        $draft = $this->draft($request);
        $validated = Validator::make(
            [
                ...($draft['fields'] ?? []),
                ...$request->validated(),
            ],
            StoreDriverSignupRequest::finalRules(),
        )->validate();

        $driverData = Arr::except($validated, array_keys(Driver::PHOTO_FIELDS));

        $driverData['approval_status'] = 'pending';
        $driverData['consented_to_background_check'] = true;
        $driverData['accepted_terms'] = true;
        $driverData['submitted_at'] = now();

        $driver = Driver::create($driverData);
        $photoPaths = $this->moveDraftPhotosToDriver($driver, $draft['photos'] ?? []);

        if ($photoPaths !== []) {
            $driver->update($photoPaths);
        }

        $request->session()->forget(self::DRAFT_SESSION_KEY);

        return redirect()
            ->route('drivers.signup.success')
            ->with('driver_application_id', $driver->id);
    }

    /**
     * @return array{id: string, fields: array<string, mixed>, photos: array<string, string>}
     */
    private function draft(Request $request): array
    {
        $draft = $request->session()->get(self::DRAFT_SESSION_KEY);

        if (! is_array($draft) || ! isset($draft['id'])) {
            $draft = [
                'id' => (string) Str::uuid(),
                'fields' => [],
                'photos' => [],
            ];

            $request->session()->put(self::DRAFT_SESSION_KEY, $draft);
        }

        return $draft;
    }

    private function storeDraftPhoto(string $draftId, string $inputName, UploadedFile $photo): string
    {
        $extension = $photo->extension() ?: $photo->getClientOriginalExtension() ?: 'jpg';

        $path = $photo->storeAs(
            'driver-signup-drafts/'.$draftId,
            $inputName.'.'.$extension,
            ['disk' => Driver::photoDisk()],
        );

        if ($path === false) {
            throw new RuntimeException('Unable to store driver signup photo.');
        }

        return $path;
    }

    /**
     * @param  array<string, string>  $photos
     * @return array<string, string>
     */
    private function moveDraftPhotosToDriver(Driver $driver, array $photos): array
    {
        $disk = Storage::disk(Driver::photoDisk());
        $photoPaths = [];

        foreach ($photos as $inputName => $draftPath) {
            $columnName = Driver::PHOTO_FIELDS[$inputName] ?? null;

            if ($columnName === null || ! $disk->exists($draftPath)) {
                continue;
            }

            $driverPath = $driver->documentDirectory().'/'.basename($draftPath);

            if (! $disk->move($draftPath, $driverPath)) {
                throw new RuntimeException('Unable to store driver signup photo.');
            }

            $photoPaths[$columnName] = $driverPath;
        }

        return $photoPaths;
    }

    private function deleteDraftPhoto(?string $path): void
    {
        if ($path !== null) {
            Storage::disk(Driver::photoDisk())->delete($path);
        }
    }

    private function ensureValidStep(string $step): void
    {
        abort_unless(array_key_exists($step, self::STEPS), 404);
    }

    private function nextStep(string $step): string
    {
        $steps = array_keys(self::STEPS);
        $currentIndex = array_search($step, $steps, true);

        if ($currentIndex === false || ! isset($steps[$currentIndex + 1])) {
            return 'review';
        }

        return $steps[$currentIndex + 1];
    }
}
