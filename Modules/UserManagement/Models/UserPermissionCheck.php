<?php

namespace Modules\UserManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class UserPermissionCheck extends Authenticatable
{
    use HasFactory;
	protected $hidden = [
        'created_at',
        'updated_at',
    ];
}