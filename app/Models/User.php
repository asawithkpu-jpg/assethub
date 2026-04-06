<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'tb_users';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nip_nik', 'name', 'jabatan', 'subbagian', 'instansi', 'hp', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}