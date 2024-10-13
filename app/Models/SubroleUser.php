<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class SubroleUser extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'parent_id',
        'admin_id',
        'role_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'country_code',
        'contact_no',
        'password',
        'image',
        'wallet_money',
        'status',
        'device_status',
        'device_id',
        'iosdevice_id',
        'create_child',
    ];
}
