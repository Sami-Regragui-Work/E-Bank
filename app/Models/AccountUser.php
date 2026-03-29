<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountUser extends Pivot
{
    public $incrementing = false;
    protected $primaryKey = [
        'user_id',
        'account_id'
    ];
    protected $fillable = [
        'user_id',
        'account_id',
        'role_id',
        'accept_closure'
    ];

    protected function casts(): array
    {
        return [
            'accept_closure' => 'boolean',
        ];
    }

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Role Helpers
    public function isOwner(): bool
    {
        return $this->role->name === Role::OWNER;
    }
    public function isGuardian(): bool
    {
        return $this->role->name === Role::GUARDIAN;
    }
}
