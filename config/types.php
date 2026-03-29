<?php

return [
    'ids' => [
        'CURRENT' => 1,
        'SAVINGS' => 2,
        'MINOR' => 3,
    ],
    'defaults' => [
        'CURRENT' => [
            'overdraft_limit' => 5000.00,
            'monthly_fee' => 50.00,
            'interest_rate' => 0.0000,
        ],
        'SAVINGS' => [
            'overdraft_limit' => 0.00,
            'monthly_fee' => 0.00,
            'interest_rate' => 0.0350,
        ],
        'MINOR' => [
            'overdraft_limit' => 0.00,
            'monthly_fee' => 0.00,
            'interest_rate' => 0.0200,
        ],
    ],
];