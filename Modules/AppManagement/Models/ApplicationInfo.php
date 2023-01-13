<?php
namespace Modules\AppManagement\Models;

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
    protected $appends = ['brandLogo', 'favIconLight', 'favIconDark'];

    public function getBrandLogoAttribute()
    {
        if ($this->getFirstMedia('logo') != null) {
            if ($this->getFirstMedia('logo')->hasGeneratedConversion('medium')) {
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

    public function getFavIconLightAttribute()
    {
        if ($this->getFirstMedia('fav-icon-light') != null) {
            if ($this->getFirstMedia('fav-icon-light')->hasGeneratedConversion('thumb')) {
                $fullPath = $this->getFirstMedia('fav-icon-light')->getPath('thumb');
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('fav-icon-light')->getUrl('thumb');
                }
            } else {
                $fullPath = $this->getFirstMedia('fav-icon-light')->getPath();
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('fav-icon-light')->getUrl();
                }
            }
        }
        return null;
    }

    public function getFavIconDarkAttribute()
    {
        if ($this->getFirstMedia('fav-icon-dark') != null) {
            if ($this->getFirstMedia('fav-icon-dark')->hasGeneratedConversion('thumb')) {
                $fullPath = $this->getFirstMedia('fav-icon-dark')->getPath('thumb');
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('fav-icon-dark')->getUrl('thumb');
                }
            } else {
                $fullPath = $this->getFirstMedia('fav-icon-dark')->getPath();
                if (file_exists($fullPath)) {
                    return $this->getFirstMedia('fav-icon-dark')->getUrl();
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
        $this->addMediaCollection('fav-icon-light')
            ->acceptsMimeTypes(['image/jpeg', 'image/svg+xml', 'image/png'])
            ->singleFile();
        $this->addMediaCollection('fav-icon-dark')
            ->acceptsMimeTypes(['image/jpeg', 'image/svg+xml', 'image/png'])
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_MAX, 250, 250)
            ->sharpen(10)
            ->keepOriginalImageFormat();

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 600, 600)
            ->keepOriginalImageFormat();
    }
}
