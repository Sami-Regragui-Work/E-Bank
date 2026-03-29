<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    // Relations
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invitation) {
            $invitation->token ??= Str::random(60);
        });
    }
}
