<?php

namespace Modules\UserRole\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserRole extends Authenticatable
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
