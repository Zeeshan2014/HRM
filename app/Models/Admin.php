<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;

class Admin extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    // You may want to define fillable properties
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // If you're using timestamps
    public $timestamps = true;

    // Optionally, you might want to hash passwords when saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($admin) {
            if (isset($admin->password)) {
                $admin->password = bcrypt($admin->password);
            }
        });
    }
}
