<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rider_saved_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Home');
            $table->string('recipient_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('city');
            $table->string('area');
            $table->string('street');
            $table->string('building');
            $table->string('floor');
            $table->string('apartment');
            $table->string('landmark')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_saved_addresses');
    }
};
