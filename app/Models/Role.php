<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'created_by_role_id',
        'created_by_admin_id',
        'created_by_subroleuser_id',
    ];


    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id', 'id');
    }
}
