<?php

namespace Modules\DataTable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\DataTable\Database\Factories\DataTableFactory;

class DataTable extends Authenticatable
{
    use HasFactory;

    protected static function newFactory()
    {
        return DataTableFactory::new();
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
