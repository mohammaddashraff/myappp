<?php

namespace Database\Seeders;

use App\Models\MotorcycleDocument;
use Illuminate\Database\Seeder;

class MotorcycleDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MotorcycleDocument::factory()->count(6)->create();
    }
}
