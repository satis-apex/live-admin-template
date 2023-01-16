<?php
namespace Modules\EventManagement\Observers;

use Illuminate\Support\Carbon;
use Modules\UserManagement\Models\User;
use Illuminate\Support\Facades\Notification;
use Modules\EventManagement\Models\Announcement;
use Modules\EventManagement\Notifications\NewAnnouncementNotification;
use Modules\EventManagement\Notifications\AnnouncementUpdateNotification;
use Modules\EventManagement\Notifications\AnnouncementCancelNotification;

class AnnouncementObserver
{
    public function created(Announcement $announcement)
    {
        if ($announcement->notify) {
            $date = Carbon::parse($announcement->start);
            if (!$date->isPast()) {
                $users = User::with('roles')
                ->whereHas('roles', function ($query) use ($announcement) {
                    $query->whereIn('name', explode(',', $announcement->viewer));
                })->get();
                Notification::send($users, new NewAnnouncementNotification($announcement));
            }
        }
    }

    public function updated(Announcement $announcement)
    {
        if ($announcement->notify) {
            $date = Carbon::parse($announcement->start);
            if (!$date->isPast()) {
                $users = User::with('roles')
                ->whereHas('roles', function ($query) use ($announcement) {
                    $query->whereIn('name', explode(',', $announcement->viewer));
                })->get();
                Notification::send($users, new AnnouncementUpdateNotification($announcement));
            }
        }
    }

    public function deleting(Announcement $announcement)
    {
        if ($announcement->notify) {
            $date = Carbon::parse($announcement->start);
            if (!$date->isPast()) {
                $users = User::with('roles')
                    ->whereHas('roles', function ($query) use ($announcement) {
                        $query->whereIn('name', explode(',', $announcement->viewer));
                    })->get();
                Notification::send($users, new AnnouncementCancelNotification($announcement));
            }
        }
    }
}
