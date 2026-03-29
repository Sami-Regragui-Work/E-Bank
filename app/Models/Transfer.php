<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'sender_id',
        'sender_account_id',
        'receiver_account_id',
        'amount',
        'status',
    ];

    protected function casts()
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    // Status constants
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_FAILED = 'FAILED';

    // Relations
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function senderAccount()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }
    public function receiverAccount()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    public function scopeOutgoing(Builder $query, Account $account): Builder
    {
        return $query->where('sender_account_id', $account->id);
    }
    public function scopeIncoming(Builder $query, Account $account): Builder
    {
        return $query->where('receiver_account_id', $account->id);
    }

    // Status Helpers
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
