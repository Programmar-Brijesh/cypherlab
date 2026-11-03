<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

   protected $fillable = [
    'name',
    'phone',
    'email',
    'password',
    'otp',
    'is_verified',
    'is_admin',
    'google_id',
];

    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }
}