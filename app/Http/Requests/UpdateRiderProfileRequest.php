<?php

namespace App\Http\Requests;

use App\Models\Rider;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRiderProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $riderId = $this->user()?->rider?->id;
        $phoneRule = ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{8,30}$/'];

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()?->id),
            ],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'current_address' => ['required', 'string', 'max:1000'],
            'phone_number' => [
                'required',
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]{8,30}$/',
                Rule::unique(Rider::class, 'phone_number')->ignore($riderId),
            ],
            'backup_phone_number' => $phoneRule,
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:120'],
            'emergency_contact_phone' => $phoneRule,
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
            'full_name' => 'الاسم الكامل',
            'email' => 'البريد الإلكتروني',
            'date_of_birth' => 'تاريخ الميلاد',
            'current_address' => 'العنوان الحالي',
            'phone_number' => 'رقم الهاتف',
            'backup_phone_number' => 'رقم الهاتف الاحتياطي',
            'emergency_contact_name' => 'اسم جهة اتصال الطوارئ',
            'emergency_contact_relationship' => 'صلة جهة اتصال الطوارئ',
            'emergency_contact_phone' => 'هاتف جهة اتصال الطوارئ',
        ];
    }
}
