<?php
namespace Modules\AppSetting\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Image\Manipulations;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ApplicationInfo extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable ,InteractsWithMedia;
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $appends = ['brandLogo', 'favIcon'];

    public function getBrandLogoAttribute()
    {
        if ($this->getFirstMedia('logo') != null) {
            if ($this->getFirstMedia('logo')->hasGeneratedConversion('thumb')) {
                $fullPath = $this->getFirstMedia('logo')->getPath('thumb');
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('logo')->getUrl('thumb');
                }
            } else {
                $fullPath = $this->getFirstMedia('logo')->getPath();
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('logo')->getUrl();
                }
            }
        }
        return null;
    }

    public function getFavIconAttribute()
    {
        if ($this->getFirstMedia('fav-icon') != null) {
            if ($this->getFirstMedia('fav-icon')->hasGeneratedConversion('thumb')) {
                $fullPath = $this->getFirstMedia('fav-icon')->getPath('thumb');
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('fav-icon')->getUrl('thumb');
                }
            } else {
                $fullPath = $this->getFirstMedia('fav-icon')->getPath();
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('fav-icon')->getUrl();
                }
            }
        }
        return null;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->acceptsMimeTypes(['image/jpeg', 'image/svg+xml', 'image/png'])
            ->singleFile();
        $this->addMediaCollection('fav-icon')
            ->acceptsMimeTypes(['image/jpeg', 'image/svg+xml', 'image/png'])
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_MAX, 150, 150)
            ->sharpen(10)
            ->keepOriginalImageFormat();

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 600, 600)
            ->keepOriginalImageFormat();
    }
}
