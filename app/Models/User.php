<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'birthdate',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'is_admin' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // JWT getters
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    // Relations
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_users')->using(AccountUser::class)->withTimestamps();
    }
    public function blockedAccountActions()
    {
        return $this->hasMany(BlockedAccount::class, 'admin_id');
    }
    public function sentTransfers()
    {
        return $this->hasMany(Transfer::class, 'sender_id');
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'sender_id');
    }
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'user_id');
    }
    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'sender_id');
    }

    // Age Helpers
    public function age(): int
    {
        return $this->birthdate->age ?? 0;
    }

    public function isMinor(): bool
    {
        return $this->age() < 18;
    }
}
