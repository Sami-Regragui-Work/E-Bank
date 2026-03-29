<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'overdraft_limit',
        'monthly_fee',
        'interest_rate',
        'default_daily_transaction_limit',
        'default_monthly_withdrawal_limit',
    ];

    protected function casts(): array
    {
        return [
            'overdraft_limit' => 'decimal:2',
            'monthly_fee' => 'decimal:2',
            'interest_rate' => 'decimal:4',
            'default_daily_transaction_limit' => 'decimal:2',
            'default_monthly_withdrawal_limit' => 'integer',
        ];
    }

    // Type Constants
    public const CURRENT = 'CURRENT';
    public const SAVINGS = 'SAVINGS';
    public const MINOR = 'MINOR';

    // Relations
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    // Type Helpers
    public function isCurrent(): bool
    {
        return $this->name === self::CURRENT;
    }
    public function isSavings(): bool
    {
        return $this->name === self::SAVINGS;
    }
    public function isMinor(): bool
    {
        return $this->name === self::MINOR;
    }

    // Attribute
    public function getFormattedInterestRate(): string
    {
        return number_format($this->interest_rate * 100, 2) . '%';
    }
}
