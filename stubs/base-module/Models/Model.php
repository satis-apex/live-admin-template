<?php

namespace Modules\{Module}\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\{Module}\Database\Factories\{Model}Factory;

class {Model} extends Authenticatable
{
    use HasFactory;

    protected static function newFactory()
    {
        return {Model}Factory::new();
    }
    protected $table = '{Model_}s';
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
