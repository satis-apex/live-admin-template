<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'guard_name',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
