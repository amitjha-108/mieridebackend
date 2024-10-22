<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'source',
        'destination',
        'four_seater_price',
        'six_seater_price',
        'sharing_price',
        'drive_price',
        'test_location',
    ];
}
