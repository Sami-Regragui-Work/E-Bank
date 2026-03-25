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
}
