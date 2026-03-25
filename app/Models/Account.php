<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'RIB',
        'daily_transaction_limit',
        'monthly_withdrawal_limit',
        'balance',
        'status',
        'type_id',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'daily_transaction_limit' => 'decimal:2',
            'monthly_withdrawal_limit' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_BLOCKED = 'BLOCKED';
    public const STATUS_CLOSED = 'CLOSED';

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'account_users')
            ->using(AccountUser::class)
            ->withTimestamps();
    }

    public function blockedAccount()
    {
        return $this->hasOne(BlockedAccount::class);
    }

    public function outgoingTransfers()
    {
        return $this->hasMany(Transfer::class, 'sender_account_id');
    }

    public function incomingTransfers()
    {
        return $this->hasMany(Transfer::class, 'receiver_account_id');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
