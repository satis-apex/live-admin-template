<?php
namespace Modules\UserManagement\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function profile()
    {
        return $this->morphTo('profileable');
    }

    protected function getDefaultGuardName(): string
    {
        return 'web';
    }

    public function receivesBroadcastNotificationsOn()
    {
        return ['announcement.' . $this->id];
    }
}
