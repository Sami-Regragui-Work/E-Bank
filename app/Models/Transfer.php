<?php

namespace App\Models;

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

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_FAILED = 'FAILED';

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
}
