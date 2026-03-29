<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => Type::CURRENT,
                'overdraft_limit' => 5000.00,
                'monthly_fee' => 50.00,
                'interest_rate' => 0.0000,
                'default_daily_transaction_limit' => 10000.00,
                'default_monthly_withdrawal_limit' => 0,
            ],
            [
                'name' => Type::SAVINGS,
                'overdraft_limit' => 0.00,
                'monthly_fee' => 0.00,
                'interest_rate' => 0.0350,
                'default_daily_transaction_limit' => 10000.00,
                'default_monthly_withdrawal_limit' => 3,
            ],
            [
                'name' => Type::MINOR,
                'overdraft_limit' => 0.00,
                'monthly_fee' => 0.00,
                'interest_rate' => 0.0200,
                'default_daily_transaction_limit' => 10000.00,
                'default_monthly_withdrawal_limit' => 2,
            ],
        ];

        foreach ($types as $type) {
            Type::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
