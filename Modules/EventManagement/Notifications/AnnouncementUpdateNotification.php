<?php
namespace Modules\EventManagement\Notifications;

use Facades\Modules\EventManagement\Services\AnnouncementService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Modules\EventManagement\Models\Announcement;

class AnnouncementUpdateNotification extends Notification
{
    // use Queueable;

    protected $announcement;
    protected $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
        $this->notification = AnnouncementService::NotificationUpdateMessage($announcement);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database',  'broadcast'];
    }

    public function toArray($notifiable)
    {
        $event = $this->announcement->holiday ? 'Holiday' : 'Announcement';

        return[
            'title' => $this->notification->title,
            'message' => $this->notification->message,
            'link' => $this->notification->link
        ];
    }

    public function toBroadcast($notifiable)
    {
        $notification = [
            'data' => [
                'title' => $this->notification->title,
                'message' => $this->notification->message,
                'link' => $this->notification->link
            ]
        ];
        return new BroadcastMessage([
            'notification' => $notification
        ]);
    }

    public function toMail($notifiable)
    {
        // code...
    }
}
