<?php

namespace App\Models;

use Database\Factories\DriverFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'legal_name',
    'date_of_birth',
    'current_address',
    'phone_number',
    'backup_phone_number',
    'emergency_contact_name',
    'emergency_contact_relationship',
    'emergency_contact_phone',
    'plate_number',
    'vehicle_owner_name',
    'chassis_number',
    'motor_number',
    'approval_status',
    'consented_to_background_check',
    'accepted_terms',
    'submitted_at',
    'national_id_front_photo_path',
    'national_id_back_photo_path',
    'selfie_photo_path',
    'driver_license_photo_path',
    'vehicle_license_photo_path',
    'criminal_record_certificate_photo_path',
    'drug_test_photo_path',
    'vehicle_front_photo_path',
    'vehicle_side_photo_path',
    'vehicle_back_photo_path',
    'delivery_box_photo_path',
])]
class Driver extends Model
{
    /** @use HasFactory<DriverFactory> */
    use HasFactory;

    public const PHOTO_DISK = 'local';

    /**
     * @var array<string, string>
     */
    public const PHOTO_FIELDS = [
        'national_id_front_photo' => 'national_id_front_photo_path',
        'national_id_back_photo' => 'national_id_back_photo_path',
        'selfie_photo' => 'selfie_photo_path',
        'driver_license_photo' => 'driver_license_photo_path',
        'vehicle_license_photo' => 'vehicle_license_photo_path',
        'criminal_record_certificate_photo' => 'criminal_record_certificate_photo_path',
        'drug_test_photo' => 'drug_test_photo_path',
        'vehicle_front_photo' => 'vehicle_front_photo_path',
        'vehicle_side_photo' => 'vehicle_side_photo_path',
        'vehicle_back_photo' => 'vehicle_back_photo_path',
        'delivery_box_photo' => 'delivery_box_photo_path',
    ];

    public function documentDirectory(): string
    {
        return 'driver-documents/driver-'.$this->id.'-'.Str::slug($this->legal_name);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'consented_to_background_check' => 'boolean',
            'accepted_terms' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }
}
