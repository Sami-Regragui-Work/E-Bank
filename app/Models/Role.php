<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public const OWNER = 'OWNER';
    public const GUARDIAN = 'GUARDIAN';
    public const MINOR = 'MINOR';

    public function accountUsers()
    {
        return $this->hasMany(AccountUser::class);
    }

}
