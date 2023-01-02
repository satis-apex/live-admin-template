<?php
namespace Modules\UserManagement\Models;

use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserInfo extends Authenticatable implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['fullName', 'avatar'];
    protected $table = 'user_infos';

    public function account()
    {
        return $this->morphOne(User::class, 'profileable');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name;
    }

    public function setJoinedDateAttribute()
    {
        return $this->joined_date = Carbon::now();
    }

    /**
     * Single File uploading
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg'])
            ->singleFile();
    }

    /**
     * @return mixed
     */
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
