<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'account_id',
        'amount',
    ];

    protected function casts()
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
