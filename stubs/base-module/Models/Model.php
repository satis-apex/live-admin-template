<?php

namespace Modules\{Module}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class {Model} extends Authenticatable
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
