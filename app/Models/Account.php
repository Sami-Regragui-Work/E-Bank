<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Account extends Model
{
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

    // Status Constants
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_BLOCKED = 'BLOCKED';
    public const STATUS_CLOSED = 'CLOSED';

    // Relations
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'account_users')->using(AccountUser::class)->withTimestamps();
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

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('users', fn($q) => $q->where('user_id', $user->id));
    }

    // Status Helpers
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }
    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    // Type Helpers
    public function isCurrent(): bool
    {
        return $this->type->isCurrent();
    }
    public function isSavings(): bool
    {
        return $this->type->isSavings();
    }
    public function isMinor(): bool
    {
        return $this->type->isMinor();
    }
}
