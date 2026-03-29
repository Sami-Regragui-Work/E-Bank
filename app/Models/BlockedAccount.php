<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedAccount extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['account_id'];
    protected $fillable = [
        'account_id',
        'admin_id',
        'blocked_at',
        'reason',
        'fee_failed',
    ];

    protected function casts(): array
    {
        return [
            'blocked_at' => 'datetime',
            'fee_failed' => 'boolean',
        ];
    }

    // Relations
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
