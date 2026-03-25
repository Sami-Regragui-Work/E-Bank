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

    public const COURANT = 'COURANT';
    public const EPARGNE = 'EPARGNE';
    public const MINEUR = 'MINEUR';

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    // Helper
    public function isCourant(): bool
    {
        return $this->name === self::COURANT;
    }

    public function isEpargne(): bool
    {
        return $this->name === self::EPARGNE;
    }

    public function isMineur(): bool
    {
        return $this->name === self::MINEUR;
    }

    public function allowsOverdraft(): bool
    {
        return $this->isCourant() && $this->overdraft_limit > 0;
    }

    public function hasMonthlyFee(): bool
    {
        return $this->isCourant() && $this->monthly_fee > 0;
    }

    public function hasInterestRate(): bool
    {
        return ($this->isEpargne() || $this->isMineur()) && $this->interest_rate > 0;
    }
}
