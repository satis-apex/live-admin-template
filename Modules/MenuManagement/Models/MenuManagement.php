<?php

namespace Modules\MenuManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\MenuManagement\Database\Factories\MenuManagementFactory;

class MenuManagement extends Authenticatable
{
    use HasFactory;

    protected static function newFactory()
    {
        return MenuManagementFactory::new();
    }
    protected $table = 'menu_managements';
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
