<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'flag',
        'country_code',
        'contact',
        'password',
        'driver_lat',
        'driver_long',
        'vehicle_id',
        'vehicle_name',
        'vehicle_brand',
        'vehicle_colour',
        'vehicle_size',
        'vehicle_no',
        'vehicle_date',
        'image',
        'status',
        'ownership_image',
        'ownership_status',
        'licence_image',
        'licence_expiry',
        'licence_status',
        'insurance_image',
        'insurance_expiry',
        'insurance_status',
        'wallet_balance',
        'verified',
        'verification_code',
        'device_status',
        'driver_device_id',
        'iosdriver_device_id',
        'login_status',
        'login_device_key',
        'access_token',
        'otp',
        'is_login',
    ];
}
