<?php
namespace Modules\EventManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\EventManagement\Database\Factories\AnnouncementFactory;

class Announcement extends Authenticatable
{
    use HasFactory;

    protected static function newFactory()
    {
        return AnnouncementFactory::new();
    }
    protected $table = 'announcements';
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
