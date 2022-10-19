<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ApplicationInfo extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
