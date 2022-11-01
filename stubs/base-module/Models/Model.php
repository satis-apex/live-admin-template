<?php

namespace Modules\{Module}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class {Model} extends Authenticatable
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
