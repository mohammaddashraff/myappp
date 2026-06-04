<?php

namespace Database\Seeders;

use App\Models\Dealer;
use App\Models\DealerMotorcycle;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->products() as $product) {
            Product::query()->updateOrCreate(
                ['name' => $product['name']],
                $product,
            );
        }

        foreach ($this->services() as $service) {
            Service::query()->updateOrCreate(
                ['name' => $service['name'], 'service_center_name' => $service['service_center_name']],
                $service,
            );
        }

        foreach ($this->dealers() as $dealerData) {
            $motorcycles = $dealerData['motorcycles'];
            unset($dealerData['motorcycles']);

            $dealer = Dealer::query()->updateOrCreate(
                ['name' => $dealerData['name']],
                $dealerData,
            );

            foreach ($motorcycles as $motorcycle) {
                DealerMotorcycle::query()->updateOrCreate(
                    [
                        'dealer_id' => $dealer->id,
                        'brand' => $motorcycle['brand'],
                        'model' => $motorcycle['model'],
                        'year' => $motorcycle['year'],
                    ],
                    $motorcycle,
                );
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function products(): array
    {
        $base = [
            'condition' => 'new',
            'delivery_available' => true,
            'pickup_available' => true,
            'installation_available' => false,
            'compatible_motorcycle_types' => ['motorcycle', 'scooter'],
            'compatible_motorcycle_brands' => ['Honda', 'Yamaha', 'SYM', 'TVS'],
            'compatible_motorcycle_models' => ['CBR', 'PCX', 'R15', 'NMAX', 'Orbit', 'HLX'],
            'estimated_delivery_time' => '2-4 days',
            'warranty_info' => '7 days store warranty',
            'return_policy' => 'Return within 14 days if unused',
            'status' => 'active',
        ];

        return [
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'Apex Full Face Helmet',
                'description' => 'A lightweight full face helmet with clear visor, removable liner, and daily commute ventilation.',
                'category' => 'Helmets',
                'brand' => 'LS2',
                'price' => 3200,
                'stock_quantity' => 8,
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=80',
                'location' => 'Cairo',
                'seller_name' => 'RideZone Store',
            ],
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'Urban Grip Riding Gloves',
                'description' => 'Breathable gloves with padded palms and touchscreen fingertips for city riding.',
                'category' => 'Gloves',
                'brand' => 'Scoyco',
                'price' => 850,
                'stock_quantity' => 14,
                'image' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?auto=format&fit=crop&w=900&q=80',
                'location' => 'Giza',
                'seller_name' => 'MotoHub Giza',
            ],
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'All Weather Riding Jacket',
                'description' => 'Protective mesh riding jacket with removable rain liner and shoulder armor.',
                'category' => 'Jackets',
                'brand' => 'Komine',
                'price' => 4700,
                'stock_quantity' => 5,
                'image' => 'https://images.unsplash.com/photo-1520975682031-a6e1d0f4f589?auto=format&fit=crop&w=900&q=80',
                'location' => 'Alexandria',
                'seller_name' => 'Coastal Riders',
            ],
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'Locking Phone Holder Pro',
                'description' => 'Vibration-resistant handlebar phone mount with quick lock release.',
                'category' => 'Phone Holders',
                'brand' => 'Baseus',
                'price' => 420,
                'stock_quantity' => 22,
                'image' => 'https://images.unsplash.com/photo-1616422285623-13ff0162193c?auto=format&fit=crop&w=900&q=80',
                'location' => 'Cairo',
                'seller_name' => 'Gear Point',
            ],
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'Rear Storage Box 45L',
                'description' => 'Hard rear top box with universal mounting plate and two keys.',
                'category' => 'Boxes',
                'brand' => 'Givi',
                'price' => 3900,
                'stock_quantity' => 6,
                'image' => 'https://images.unsplash.com/photo-1591637333184-19aa84b3e01f?auto=format&fit=crop&w=900&q=80',
                'location' => 'Mansoura',
                'seller_name' => 'Delta Moto',
            ],
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'Waterproof Motorcycle Cover',
                'description' => 'UV and rain resistant motorcycle cover with buckle strap for outdoor parking.',
                'category' => 'Covers',
                'brand' => 'Oxford',
                'price' => 650,
                'stock_quantity' => 18,
                'image' => 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?auto=format&fit=crop&w=900&q=80',
                'location' => 'Cairo',
                'seller_name' => 'RideZone Store',
            ],
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'Disc Brake Security Lock',
                'description' => 'Compact disc lock with reminder cable and hardened steel pin.',
                'category' => 'Locks',
                'brand' => 'Abus',
                'price' => 920,
                'stock_quantity' => 10,
                'image' => 'https://images.unsplash.com/photo-1575550959106-5a7defe28b56?auto=format&fit=crop&w=900&q=80',
                'location' => 'Giza',
                'seller_name' => 'MotoHub Giza',
            ],
            $base + [
                'type' => Product::TYPE_ACCESSORY,
                'name' => 'Compact Rain Gear Set',
                'description' => 'Two-piece waterproof riding rain suit packed in a small seat bag.',
                'category' => 'Rain Gear',
                'brand' => 'Richa',
                'price' => 1250,
                'stock_quantity' => 9,
                'image' => 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?auto=format&fit=crop&w=900&q=80',
                'location' => 'Alexandria',
                'seller_name' => 'Coastal Riders',
            ],
            $base + [
                'type' => Product::TYPE_SPARE_PART,
                'name' => 'City Grip Front Tire 90/90-14',
                'description' => 'Durable scooter front tire designed for wet city streets and daily commuting.',
                'category' => 'Tires',
                'brand' => 'Michelin',
                'price' => 1850,
                'stock_quantity' => 12,
                'image' => 'https://images.unsplash.com/photo-1558981285-6f0c94958bb6?auto=format&fit=crop&w=900&q=80',
                'location' => 'Cairo',
                'seller_name' => 'TireMax Egypt',
            ],
            $base + [
                'type' => Product::TYPE_SPARE_PART,
                'name' => 'Performance Brake Pads Set',
                'description' => 'Front brake pad set with strong bite and low dust for commuter motorcycles.',
                'category' => 'Brakes',
                'brand' => 'Brembo',
                'price' => 780,
                'stock_quantity' => 16,
                'image' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=900&q=80',
                'location' => 'Giza',
                'seller_name' => 'Brake House',
            ],
            $base + [
                'type' => Product::TYPE_SPARE_PART,
                'name' => 'Heavy Duty Chain Kit',
                'description' => 'Chain and sprocket kit for 150cc to 200cc commuter motorcycles.',
                'category' => 'Chains',
                'brand' => 'DID',
                'price' => 1650,
                'stock_quantity' => 7,
                'image' => 'https://images.unsplash.com/photo-1608492989260-4aa7fe6f84b7?auto=format&fit=crop&w=900&q=80',
                'location' => 'Cairo',
                'seller_name' => 'Moto Parts Central',
            ],
            $base + [
                'type' => Product::TYPE_SPARE_PART,
                'name' => 'Synthetic Engine Oil 10W-40',
                'description' => 'One liter synthetic motorcycle oil for smoother shifts and engine protection.',
                'category' => 'Oils',
                'brand' => 'Motul',
                'price' => 420,
                'stock_quantity' => 35,
                'image' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=900&q=80',
                'location' => 'Alexandria',
                'seller_name' => 'Coastal Riders',
            ],
            $base + [
                'type' => Product::TYPE_SPARE_PART,
                'name' => 'Iridium Spark Plug CR8EIX',
                'description' => 'Iridium spark plug for improved ignition response and longer service life.',
                'category' => 'Spark Plugs',
                'brand' => 'NGK',
                'price' => 360,
                'stock_quantity' => 24,
                'image' => 'https://images.unsplash.com/photo-1581092335397-9583eb92d232?auto=format&fit=crop&w=900&q=80',
                'location' => 'Cairo',
                'seller_name' => 'Moto Parts Central',
            ],
            $base + [
                'type' => Product::TYPE_SPARE_PART,
                'name' => 'Air Filter Element',
                'description' => 'Replacement air filter element for common commuter motorcycles and scooters.',
                'category' => 'Filters',
                'brand' => 'HifloFiltro',
                'price' => 290,
                'stock_quantity' => 20,
                'image' => 'https://images.unsplash.com/photo-1632823471565-1ecdf5c6bfef?auto=format&fit=crop&w=900&q=80',
                'location' => 'Mansoura',
                'seller_name' => 'Delta Moto',
            ],
            $base + [
                'type' => Product::TYPE_BATTERY,
                'name' => 'VoltX AGM Battery 12V 7Ah',
                'description' => 'Maintenance-free AGM battery for scooters and light commuter motorcycles.',
                'category' => 'Batteries',
                'brand' => 'VoltX',
                'price' => 1450,
                'stock_quantity' => 11,
                'image' => 'https://images.unsplash.com/photo-1606041011872-596597976b25?auto=format&fit=crop&w=900&q=80',
                'location' => 'Cairo',
                'seller_name' => 'Battery Rescue Cairo',
                'installation_available' => true,
                'voltage' => '12V',
                'capacity' => '7Ah',
                'warranty_info' => '12 months replacement warranty',
            ],
            $base + [
                'type' => Product::TYPE_BATTERY,
                'name' => 'Yuasa Starter Battery 12V 9Ah',
                'description' => 'Reliable starter battery for mid-size motorcycles with installation support.',
                'category' => 'Batteries',
                'brand' => 'Yuasa',
                'price' => 2150,
                'stock_quantity' => 6,
                'image' => 'https://images.unsplash.com/photo-1606041011872-596597976b25?auto=format&fit=crop&w=900&q=80',
                'location' => 'Giza',
                'seller_name' => 'MotoHub Giza',
                'installation_available' => true,
                'voltage' => '12V',
                'capacity' => '9Ah',
                'warranty_info' => '12 months supplier warranty',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function services(): array
    {
        $base = [
            'pickup_available' => true,
            'available_today' => true,
            'motorcycle_types' => ['motorcycle', 'scooter'],
            'working_hours' => '10:00 AM - 8:00 PM',
            'notes' => 'Please arrive 10 minutes early and bring any previous maintenance notes.',
            'status' => 'active',
        ];

        return [
            $base + ['name' => 'Quick General Maintenance', 'category' => 'General Maintenance', 'description' => 'Basic bolts, fluid, chain, tire pressure, and safety checks.', 'estimated_price' => 450, 'estimated_duration' => '60 minutes', 'service_center_name' => 'MotoCare Cairo', 'location' => 'Cairo', 'rating' => 4.7],
            $base + ['name' => 'Premium Oil Change', 'category' => 'Oil Change', 'description' => 'Oil drain, filter inspection, synthetic oil refill, and leak check.', 'estimated_price' => 380, 'estimated_duration' => '35 minutes', 'service_center_name' => 'MotoCare Cairo', 'location' => 'Cairo', 'rating' => 4.8],
            $base + ['name' => 'Tire Change and Balance', 'category' => 'Tire Change', 'description' => 'Front or rear tire replacement with pressure and valve inspection.', 'estimated_price' => 520, 'estimated_duration' => '50 minutes', 'service_center_name' => 'TireMax Egypt', 'location' => 'Giza', 'rating' => 4.5],
            $base + ['name' => 'Brake Service Check', 'category' => 'Brake Service', 'description' => 'Brake pad, disc, fluid, and lever response inspection.', 'estimated_price' => 420, 'estimated_duration' => '45 minutes', 'service_center_name' => 'Brake House', 'location' => 'Giza', 'rating' => 4.4],
            $base + ['name' => 'Chain Clean and Adjust', 'category' => 'Chain Service', 'description' => 'Chain clean, lubrication, tension adjustment, and sprocket visual check.', 'estimated_price' => 260, 'estimated_duration' => '30 minutes', 'service_center_name' => 'Moto Parts Central', 'location' => 'Cairo', 'rating' => 4.3],
            $base + ['name' => 'Engine Inspection Visit', 'category' => 'Engine Inspection', 'description' => 'Engine sound, smoke, oil leak, compression indicators, and ride feel inspection.', 'estimated_price' => 850, 'estimated_duration' => '90 minutes', 'service_center_name' => 'Delta Moto Service', 'location' => 'Mansoura', 'rating' => 4.6],
            $base + ['name' => 'Electrical Repair Diagnosis', 'category' => 'Electrical Repair', 'description' => 'Battery, charging, lighting, switch, and wiring fault diagnosis.', 'estimated_price' => 700, 'estimated_duration' => '90 minutes', 'service_center_name' => 'Battery Rescue Cairo', 'location' => 'Cairo', 'rating' => 4.2],
            $base + ['name' => 'Full Pre-Ride Inspection', 'category' => 'Full Inspection', 'description' => 'Full visual and mechanical inspection before purchase or long rides.', 'estimated_price' => 1100, 'estimated_duration' => '2 hours', 'service_center_name' => 'Coastal Riders Service', 'location' => 'Alexandria', 'rating' => 4.9],
            $base + ['name' => 'Wash and Detailing', 'category' => 'Wash / Detailing', 'description' => 'Foam wash, degrease, panel care, and seat conditioning.', 'estimated_price' => 300, 'estimated_duration' => '45 minutes', 'service_center_name' => 'CleanRide Studio', 'location' => 'Cairo', 'rating' => 4.1],
            $base + ['name' => 'Emergency Repair Slot', 'category' => 'Emergency Repair', 'description' => 'Priority diagnosis for unexpected breakdowns and urgent repairs.', 'estimated_price' => 950, 'estimated_duration' => '2 hours', 'service_center_name' => 'MotoCare Cairo', 'location' => 'Cairo', 'rating' => 4.7],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function dealers(): array
    {
        return [
            [
                'name' => 'Cairo Moto Showroom',
                'location' => 'Nasr City, Cairo',
                'brands_available' => ['Honda', 'Yamaha', 'SYM'],
                'phone' => '+20224000011',
                'rating' => 4.6,
                'status' => 'active',
                'motorcycles' => [
                    ['brand' => 'Honda', 'model' => 'CBR150R', 'year' => 2025, 'engine_cc' => 150, 'condition' => 'new', 'price' => 168000, 'installment_available' => true, 'installment_options' => '20% down payment, 24 monthly installments.', 'description' => 'Sport commuter motorcycle with sharp styling and warranty support.', 'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=900&q=80', 'status' => 'active'],
                    ['brand' => 'SYM', 'model' => 'Jet X', 'year' => 2024, 'engine_cc' => 150, 'condition' => 'used', 'price' => 92000, 'installment_available' => false, 'installment_options' => null, 'description' => 'Clean used scooter with showroom inspection and service history.', 'image' => 'https://images.unsplash.com/photo-1558981359-219d6364c9c8?auto=format&fit=crop&w=900&q=80', 'status' => 'active'],
                ],
            ],
            [
                'name' => 'Giza Riders Gallery',
                'location' => 'Dokki, Giza',
                'brands_available' => ['Yamaha', 'TVS', 'Bajaj'],
                'phone' => '+20233000022',
                'rating' => 4.4,
                'status' => 'active',
                'motorcycles' => [
                    ['brand' => 'Yamaha', 'model' => 'NMAX', 'year' => 2025, 'engine_cc' => 155, 'condition' => 'new', 'price' => 182000, 'installment_available' => true, 'installment_options' => '25% down payment, installments up to 36 months.', 'description' => 'Comfortable premium scooter for daily city rides and longer commutes.', 'image' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?auto=format&fit=crop&w=900&q=80', 'status' => 'active'],
                    ['brand' => 'TVS', 'model' => 'Apache RTR', 'year' => 2023, 'engine_cc' => 180, 'condition' => 'used', 'price' => 78000, 'installment_available' => true, 'installment_options' => '30% down payment over 18 months.', 'description' => 'Used naked commuter with recent maintenance and good tires.', 'image' => 'https://images.unsplash.com/photo-1601579119650-4c72d7f867dd?auto=format&fit=crop&w=900&q=80', 'status' => 'active'],
                ],
            ],
            [
                'name' => 'Alexandria Coastal Motors',
                'location' => 'Smouha, Alexandria',
                'brands_available' => ['Honda', 'Bajaj'],
                'phone' => '+2035400033',
                'rating' => 4.3,
                'status' => 'active',
                'motorcycles' => [
                    ['brand' => 'Bajaj', 'model' => 'Pulsar NS200', 'year' => 2025, 'engine_cc' => 200, 'condition' => 'new', 'price' => 124000, 'installment_available' => true, 'installment_options' => '20% down payment with flexible tenor.', 'description' => 'Strong 200cc city motorcycle with dealer warranty and first-service support.', 'image' => 'https://images.unsplash.com/photo-1601579119650-4c72d7f867dd?auto=format&fit=crop&w=900&q=80', 'status' => 'active'],
                ],
            ],
        ];
    }
}
