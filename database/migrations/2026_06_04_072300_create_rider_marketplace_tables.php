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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('name');
            $table->text('description');
            $table->string('category')->index();
            $table->string('brand')->index();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('condition')->default('new')->index();
            $table->string('image')->nullable();
            $table->string('location')->index();
            $table->string('seller_name');
            $table->boolean('delivery_available')->default(false)->index();
            $table->boolean('pickup_available')->default(false)->index();
            $table->boolean('installation_available')->default(false)->index();
            $table->json('compatible_motorcycle_types')->nullable();
            $table->json('compatible_motorcycle_brands')->nullable();
            $table->json('compatible_motorcycle_models')->nullable();
            $table->string('estimated_delivery_time')->nullable();
            $table->string('warranty_info')->nullable();
            $table->string('return_policy')->nullable();
            $table->string('voltage')->nullable();
            $table->string('capacity')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->index();
            $table->text('description');
            $table->decimal('estimated_price', 10, 2);
            $table->string('estimated_duration');
            $table->string('service_center_name');
            $table->string('location')->index();
            $table->decimal('rating', 3, 2)->nullable();
            $table->string('working_hours')->nullable();
            $table->boolean('pickup_available')->default(false)->index();
            $table->boolean('available_today')->default(false)->index();
            $table->json('motorcycle_types')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->index();
            $table->json('brands_available')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('dealer_motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained()->cascadeOnDelete();
            $table->string('brand')->index();
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('engine_cc');
            $table->string('condition')->default('new')->index();
            $table->decimal('price', 12, 2);
            $table->boolean('installment_available')->default(false)->index();
            $table->text('installment_options')->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();

            $table->unique(['rider_id', 'product_id']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('delivery_method');
            $table->string('payment_method');
            $table->text('address')->nullable();
            $table->string('status')->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('product_type');
            $table->decimal('product_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });

        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('booking_number')->unique();
            $table->foreignId('motorcycle_id')->nullable()->constrained()->nullOnDelete();
            $table->date('booking_date');
            $table->time('preferred_time');
            $table->string('location_option');
            $table->text('notes')->nullable();
            $table->string('contact_phone');
            $table->decimal('estimated_price', 10, 2);
            $table->string('status')->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('roadside_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->cascadeOnDelete();
            $table->string('request_number')->unique();
            $table->string('assistance_type')->index();
            $table->foreignId('motorcycle_id')->nullable()->constrained()->nullOnDelete();
            $table->text('location');
            $table->text('description')->nullable();
            $table->string('contact_phone');
            $table->string('status')->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('battery_replacement_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->cascadeOnDelete();
            $table->string('request_number')->unique();
            $table->foreignId('battery_product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('motorcycle_id')->nullable()->constrained()->nullOnDelete();
            $table->text('location');
            $table->date('preferred_date');
            $table->time('preferred_time');
            $table->string('contact_phone');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('dealer_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dealer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dealer_motorcycle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('inquiry_number')->unique();
            $table->string('rider_name');
            $table->string('phone');
            $table->text('message');
            $table->string('preferred_contact_method');
            $table->string('status')->default('pending')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_inquiries');
        Schema::dropIfExists('battery_replacement_requests');
        Schema::dropIfExists('roadside_requests');
        Schema::dropIfExists('service_bookings');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('dealer_motorcycles');
        Schema::dropIfExists('dealers');
        Schema::dropIfExists('services');
        Schema::dropIfExists('products');
    }
};
