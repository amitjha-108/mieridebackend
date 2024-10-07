<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_name',
    ];


    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id', 'id');
    }
}
