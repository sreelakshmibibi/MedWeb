<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'route_name', 'icon', 'order_no', 'parent_id', 'status'];

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_has_role');
    }
}
