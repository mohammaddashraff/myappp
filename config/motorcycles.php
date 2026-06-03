<?php

return [
    'types' => [
        'scooter' => 'Scooter',
        'commuter' => 'Commuter',
        'delivery_motorcycle' => 'Delivery Motorcycle',
        'street_naked' => 'Street / Naked',
        'sport' => 'Sport',
        'cruiser' => 'Cruiser',
        'touring_adventure' => 'Touring / Adventure',
        'offroad_dirt' => 'Off-road / Dirt',
        'electric' => 'Electric',
        'other' => 'Other',
    ],
    'brands' => [
        [
            'name' => 'SYM',
            'country' => 'Taiwan',
            'models' => [
                ['name' => 'Orbit', 'type' => 'scooter'],
                ['name' => 'Jet', 'type' => 'scooter'],
                ['name' => 'Fiddle', 'type' => 'scooter'],
                ['name' => 'Symphony', 'type' => 'scooter'],
                ['name' => 'Symphony SR', 'type' => 'scooter'],
                ['name' => 'Symphony ST', 'type' => 'scooter'],
                ['name' => 'Cruisym', 'type' => 'touring_adventure'],
                ['name' => 'Jet X', 'type' => 'scooter'],
                ['name' => 'Husky ADV', 'type' => 'touring_adventure'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Benelli',
            'country' => 'Italy',
            'models' => [
                ['name' => 'Keet', 'type' => 'scooter'],
                ['name' => 'TNT', 'type' => 'street_naked'],
                ['name' => 'Versilia', 'type' => 'scooter'],
                ['name' => 'VLR', 'type' => 'sport'],
                ['name' => 'VLM', 'type' => 'commuter'],
                ['name' => 'Zafferano', 'type' => 'touring_adventure'],
                ['name' => 'Caffenero', 'type' => 'scooter'],
                ['name' => 'S200', 'type' => 'sport'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Bajaj',
            'country' => 'India',
            'models' => [
                ['name' => 'Boxer', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => 'Boxer BM 150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => 'Boxer X 150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => 'Pulsar', 'type' => 'street_naked'],
                ['name' => 'Pulsar 180', 'type' => 'street_naked', 'default_engine_cc' => 180],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'TVS',
            'country' => 'India',
            'models' => [
                ['name' => 'HLX', 'type' => 'commuter'],
                ['name' => 'HLX F', 'type' => 'commuter'],
                ['name' => 'Apache RTR', 'type' => 'sport'],
                ['name' => 'XL100', 'type' => 'commuter', 'default_engine_cc' => 100],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Haojue',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Haojiang',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Honda',
            'country' => 'Japan',
            'models' => [
                ['name' => 'CBR', 'type' => 'sport'],
                ['name' => 'CB', 'type' => 'street_naked'],
                ['name' => 'Hornet', 'type' => 'street_naked'],
                ['name' => 'Shadow', 'type' => 'cruiser'],
                ['name' => 'Forza', 'type' => 'scooter'],
                ['name' => 'PCX', 'type' => 'scooter'],
                ['name' => 'Dio', 'type' => 'scooter'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Yamaha',
            'country' => 'Japan',
            'models' => [
                ['name' => 'R15', 'type' => 'sport'],
                ['name' => 'R3', 'type' => 'sport'],
                ['name' => 'MT-03', 'type' => 'street_naked'],
                ['name' => 'MT-07', 'type' => 'street_naked'],
                ['name' => 'FZ', 'type' => 'street_naked'],
                ['name' => 'NMAX', 'type' => 'scooter'],
                ['name' => 'XMAX', 'type' => 'scooter'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Suzuki',
            'country' => 'Japan',
            'models' => [
                ['name' => 'Gixxer', 'type' => 'street_naked'],
                ['name' => 'GSX', 'type' => 'sport'],
                ['name' => 'Burgman', 'type' => 'scooter'],
                ['name' => 'Boulevard', 'type' => 'cruiser'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Keeway',
            'country' => 'China',
            'models' => [
                ['name' => 'RKE150', 'type' => 'street_naked', 'default_engine_cc' => 150],
                ['name' => 'XDV', 'type' => 'touring_adventure'],
                ['name' => 'Vieste', 'type' => 'scooter'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'KYMCO',
            'country' => 'Taiwan',
            'models' => [
                ['name' => 'Agility', 'type' => 'scooter'],
                ['name' => 'Urban', 'type' => 'scooter'],
                ['name' => 'X-Town', 'type' => 'touring_adventure'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Vespa',
            'country' => 'Italy',
            'models' => [
                ['name' => 'Primavera', 'type' => 'scooter'],
                ['name' => 'Sprint', 'type' => 'scooter'],
                ['name' => 'GTS', 'type' => 'scooter'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Yadea',
            'country' => 'China',
            'models' => [
                ['name' => 'C1S', 'type' => 'electric'],
                ['name' => 'G5', 'type' => 'electric'],
                ['name' => 'E8S', 'type' => 'electric'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Other',
            'country' => null,
            'models' => [
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Dayun',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Hawa',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Halawa',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Wuyang',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Pullman',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Glide',
            'country' => 'China',
            'models' => [
                ['name' => '150', 'type' => 'commuter', 'default_engine_cc' => 150],
                ['name' => '200', 'type' => 'commuter', 'default_engine_cc' => 200],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Kawasaki',
            'country' => 'Japan',
            'models' => [
                ['name' => 'Ninja', 'type' => 'sport'],
                ['name' => 'Z', 'type' => 'street_naked'],
                ['name' => 'Versys', 'type' => 'touring_adventure'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'BMW Motorrad',
            'country' => 'Germany',
            'models' => [
                ['name' => 'GS', 'type' => 'touring_adventure'],
                ['name' => 'G 310', 'type' => 'street_naked'],
                ['name' => 'S 1000 RR', 'type' => 'sport'],
                ['name' => 'R 1250', 'type' => 'touring_adventure'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Ducati',
            'country' => 'Italy',
            'models' => [
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'KTM',
            'country' => 'Austria',
            'models' => [
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Harley-Davidson',
            'country' => 'United States',
            'models' => [
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
        [
            'name' => 'Piaggio',
            'country' => 'Italy',
            'models' => [
                ['name' => 'Liberty', 'type' => 'scooter'],
                ['name' => 'Medley', 'type' => 'scooter'],
                ['name' => 'Beverly', 'type' => 'scooter'],
                ['name' => 'Other', 'type' => 'other'],
            ],
        ],
    ],
];
