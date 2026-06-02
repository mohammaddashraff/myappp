<?php

namespace App\Http\Requests;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

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
        $step = (string) $this->route('step', 'account');

        if ($step === 'account' && $this->user() !== null) {
            return [];
        }

        return self::rulesForStep($step);
    }

    /**
     * Get validation rules for a signup step.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public static function rulesForStep(string $step): array
    {
        return match ($step) {
            'account' => self::accountRules(),
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
    private static function accountRules(): array
    {
        return [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'legal_name' => 'الاسم القانوني الكامل',
            'date_of_birth' => 'تاريخ الميلاد',
            'current_address' => 'العنوان الحالي',
            'phone_number' => 'رقم الهاتف',
            'backup_phone_number' => 'رقم الهاتف الاحتياطي',
            'emergency_contact_name' => 'اسم جهة اتصال الطوارئ',
            'emergency_contact_relationship' => 'صلة جهة اتصال الطوارئ',
            'emergency_contact_phone' => 'هاتف جهة اتصال الطوارئ',
            'plate_number' => 'رقم اللوحة',
            'vehicle_owner_name' => 'اسم مالك المركبة',
            'chassis_number' => 'رقم الشاسيه',
            'motor_number' => 'رقم الموتور',
            'national_id_front_photo' => 'صورة وجه بطاقة الرقم القومي',
            'national_id_back_photo' => 'صورة ظهر بطاقة الرقم القومي',
            'selfie_photo' => 'الصورة الشخصية',
            'driver_license_photo' => 'صورة رخصة القيادة',
            'vehicle_license_photo' => 'صورة رخصة المركبة',
            'criminal_record_certificate_photo' => 'صورة صحيفة الحالة الجنائية',
            'drug_test_photo' => 'صورة تحليل المخدرات',
            'vehicle_front_photo' => 'صورة المركبة من الأمام',
            'vehicle_side_photo' => 'صورة المركبة من الجانب',
            'vehicle_back_photo' => 'صورة المركبة من الخلف',
            'delivery_box_photo' => 'صورة صندوق التوصيل',
        ];
    }
}
