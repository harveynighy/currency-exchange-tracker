<?php

return [
    'default' => 'free',

    'plans' => [
        'free' => [
            'name' => 'Free',
            'price' => 0,
            'price_label' => '£0/mo',
            'monthly_requests' => 750,
            'description' => 'Ideal for testing and light historical API usage.',
            'stripe_price_id' => null,
        ],
        'pro' => [
            'name' => 'Pro',
            'price' => 2,
            'price_label' => '£2/mo',
            'monthly_requests' => 10000,
            'description' => 'For active projects that need deeper historical data access.',
            'stripe_price_id' => env('STRIPE_PRICE_PRO'),
        ],
        'business' => [
            'name' => 'Business',
            'price' => 5,
            'price_label' => '£5/mo',
            'monthly_requests' => 50000,
            'description' => 'For production workloads and higher-volume API access.',
            'stripe_price_id' => env('STRIPE_PRICE_BUSINESS'),
        ],
    ],
];