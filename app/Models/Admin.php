<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'country_code',
        'contact_no',
        'password',
        'image',
        'wallet_money',
        'user_type',
        'status',
        'device_status',
        'device_id',
        'iosdevice_id',
    ];
}
