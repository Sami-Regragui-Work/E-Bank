<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'account_id',
        'amount',
        'is_fee',
    ];

    protected function casts()
    {
        return [
            'amount' => 'decimal:2',
            'is_fee' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
