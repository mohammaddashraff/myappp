<?php

namespace App\Http\Requests;

use App\Models\Driver;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriverSignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return self::rulesForStep((string) $this->route('step', 'identity'));
    }

    /**
     * Get validation rules for a signup step.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public static function rulesForStep(string $step): array
    {
        return match ($step) {
            'identity' => self::identityRules(),
            'contact' => self::contactRules(),
            'documents' => self::documentRules(),
            'vehicle' => self::vehicleRules(),
            'review' => self::reviewRules(),
            default => [],
        };
    }

    /**
     * Get the rules needed before a driver application can be created.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public static function finalRules(): array
    {
        return [
            ...self::identityRules(),
            ...self::contactRules(),
            ...self::vehicleRules(),
            ...self::reviewRules(),
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    private static function identityRules(): array
    {
        $minimumBirthDate = now()->subYears(18)->toDateString();

        return [
            'legal_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:'.$minimumBirthDate],
            'current_address' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    private static function contactRules(): array
    {
        $phoneRule = ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{8,30}$/'];

        return [
            'phone_number' => [...$phoneRule, Rule::unique(Driver::class, 'phone_number')],
            'backup_phone_number' => $phoneRule,
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_relationship' => ['required', 'string', 'max:120'],
            'emergency_contact_phone' => $phoneRule,
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    private static function documentRules(): array
    {
        $optionalPhotoRule = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'];

        return [
            'national_id_front_photo' => $optionalPhotoRule,
            'national_id_back_photo' => $optionalPhotoRule,
            'selfie_photo' => $optionalPhotoRule,
            'driver_license_photo' => $optionalPhotoRule,
            'vehicle_license_photo' => $optionalPhotoRule,
            'criminal_record_certificate_photo' => $optionalPhotoRule,
            'drug_test_photo' => $optionalPhotoRule,
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    private static function vehicleRules(): array
    {
        $optionalPhotoRule = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'];

        return [
            'plate_number' => ['required', 'string', 'max:40', Rule::unique(Driver::class, 'plate_number')],
            'vehicle_owner_name' => ['required', 'string', 'max:255'],
            'chassis_number' => ['required', 'string', 'max:80', Rule::unique(Driver::class, 'chassis_number')],
            'motor_number' => ['required', 'string', 'max:80', Rule::unique(Driver::class, 'motor_number')],
            'vehicle_front_photo' => $optionalPhotoRule,
            'vehicle_side_photo' => $optionalPhotoRule,
            'vehicle_back_photo' => $optionalPhotoRule,
            'delivery_box_photo' => $optionalPhotoRule,
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    private static function reviewRules(): array
    {
        return [
            'consented_to_background_check' => ['accepted'],
            'accepted_terms' => ['accepted'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'legal_name' => 'full legal name',
            'date_of_birth' => 'date of birth',
            'current_address' => 'current address',
            'phone_number' => 'phone number',
            'backup_phone_number' => 'backup phone number',
            'emergency_contact_name' => 'emergency contact name',
            'emergency_contact_relationship' => 'emergency contact relationship',
            'emergency_contact_phone' => 'emergency contact phone',
            'plate_number' => 'plate number',
            'vehicle_owner_name' => 'vehicle owner name',
            'chassis_number' => 'chassis number',
            'motor_number' => 'motor number',
            'national_id_front_photo' => 'national ID front photo',
            'national_id_back_photo' => 'national ID back photo',
            'selfie_photo' => 'selfie photo',
            'driver_license_photo' => 'driver license photo',
            'vehicle_license_photo' => 'vehicle license photo',
            'criminal_record_certificate_photo' => 'criminal record certificate photo',
            'drug_test_photo' => 'drug test photo',
            'vehicle_front_photo' => 'vehicle front photo',
            'vehicle_side_photo' => 'vehicle side photo',
            'vehicle_back_photo' => 'vehicle back photo',
            'delivery_box_photo' => 'delivery box photo',
        ];
    }
}
