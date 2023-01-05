<?php
namespace Modules\EmployeeManagement\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\EmployeeManagement\Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\UserManagement\Models\User;

class Employee extends Authenticatable implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected static function newFactory()
    {
        return EmployeeFactory::new();
    }
    protected $table = 'employees';
    protected $appends = ['fullName', 'avatar'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function account()
    {
        return $this->morphOne(User::class, 'profileable');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg'])
            ->singleFile();
    }

    public function getAvatarAttribute()
    {
        if ($this->getFirstMedia('avatar') != null) {
            $fullPath = $this->getFirstMedia('avatar')->getPath('thumb');
            if (file_exists($fullPath)) {
                return $this->getFirstMedia('avatar')->getUrl('thumb');
            }
        }
        return null;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(600)
            ->height(600);
    }
}
