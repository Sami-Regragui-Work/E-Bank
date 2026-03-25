<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public const OWNER = 'OWNER';
    public const GUARDIAN = 'GUARDIAN';
    public const MINOR = 'MINOR';

    
}
