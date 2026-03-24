<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'account_id',
        'email',
        'token',
    ];

    protected function casts()
    {
        return [
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
