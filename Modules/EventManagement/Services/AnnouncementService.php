<?php
namespace Modules\EventManagement\Services;

use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Modules\EventManagement\Models\Announcement;

class AnnouncementService
{
    public function add()
    {
        try {
            $insertData = [
                'title' => request('title'),
                'detail' => request('detail'),
                'start' => request('start'),
                'end' => request('end'),
                'allDay' => request('allDay'),
                'private' => request('private'),
                'holiday' => request('holiday'),
                'viewer' => \is_array(request('viewer')) ? implode(',', request('viewer')) : '',
                'notify' => request('notify'),
            ];
            return Announcement::create($insertData);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function massAdd()
    {
        try {
            $insertData = request('massRecord');
            foreach ($insertData as $row) {
                Announcement::create($row);
            }
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $announcement = Announcement::find($id);
            $announcement->title = request('title');
            $announcement->detail = request('detail');
            $announcement->start = request('start');
            $announcement->end = request('end');
            $announcement->private = request('private');
            $announcement->allDay = request('allDay');
            $announcement->holiday = request('holiday');
            $announcement->viewer = \is_array(request('viewer')) ? implode(',', request('viewer')) : '';
            if (request('notify') == true) {
                $announcement->notify = request('notify');
            }

            return $announcement->save();
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            return Announcement::destroy($id);
        } catch (QueryException $e) {
            return throw new  \Exception($e->errorInfo[2]);
        } catch (\Exception $e) {
            return throw new  \Exception($e->getMessage());
        }
    }

    public function newNotificationMessage($announcement)
    {
        $title = $announcement->holiday ? 'Holiday Announcement' : 'Event Announcement';
        if ($announcement->holiday) {
            $date1 = Carbon::create($announcement->start);
            $date2 = Carbon::create($announcement->end);
            $days = $date1->diffInDays($date2);
            if ($days > 1) {
                $message = $announcement->title . ' holiday announced from date : ' . Carbon::parse($announcement->start)->format('Y-m-d') . ' to ' . Carbon::parse($announcement->end)->format('Y-m-d');
            } else {
                $message = $announcement->title . ' holiday announced for date : ' . Carbon::parse($announcement->start)->format('Y-m-d');
            }
        } else {
            if ($announcement->allDay) {
                $date1 = Carbon::create($announcement->start);
                $date2 = Carbon::create($announcement->end);
                $days = $date1->diffInDays($date2);
                if ($days > 1) {
                    $message = $announcement->title . ' event announced from date : ' . Carbon::parse($announcement->start)->format('Y-m-d') . ' to ' . Carbon::parse($announcement->end)->format('Y-m-d');
                } else {
                    $message = $announcement->title . ' event announced for date : ' . Carbon::parse($announcement->start)->format('Y-m-d');
                }
            } else {
                $message = $announcement->title . ' event announced for date : ' . Carbon::parse($announcement->start)->format('Y-m-d H:i a') . ' to ' . Carbon::parse($announcement->end)->format('H:i a');
            }
        }
        $link = route('announcement.index');
        return (object) $announcement = ['title' => $title, 'message' => $message, 'link' => $link];
    }

    public function notificationUpdateMessage($announcement)
    {
        $title = $announcement->holiday ? 'Updated Holiday Announcement' : 'Updated Event Announcement';
        if ($announcement->holiday) {
            $date1 = Carbon::create($announcement->start);
            $date2 = Carbon::create($announcement->end);
            $days = $date1->diffInDays($date2);
            if ($days > 1) {
                $message = 'Update! ' . $announcement->title . ' holiday has been rescheduled to take place from date: ' . Carbon::parse($announcement->start)->format('Y-m-d') . ' to ' . Carbon::parse($announcement->end)->format('Y-m-d');
            } else {
                $message = 'Update! ' . $announcement->title . ' holiday has been rescheduled to take place on date : ' . Carbon::parse($announcement->start)->format('Y-m-d');
            }
        } else {
            if ($announcement->allDay) {
                $date1 = Carbon::create($announcement->start);
                $date2 = Carbon::create($announcement->end);
                $days = $date1->diffInDays($date2);
                if ($days > 1) {
                    $message = 'Update! ' . $announcement->title . ' event has been rescheduled to take place from date : ' . Carbon::parse($announcement->start)->format('Y-m-d') . ' to ' . Carbon::parse($announcement->end)->format('Y-m-d');
                } else {
                    $message = 'Update! ' . $announcement->title . ' event has been rescheduled to take place on date : ' . Carbon::parse($announcement->start)->format('Y-m-d');
                }
            } else {
                $message = 'Update! ' . $announcement->title . ' event has been rescheduled to take place on date : ' . Carbon::parse($announcement->start)->format('Y-m-d H:i a') . ' to ' . Carbon::parse($announcement->end)->format('H:i a');
            }
        }
        $link = route('announcement.index');
        return (object) $announcement = ['title' => $title, 'message' => $message, 'link' => $link];
    }

    public function notificationCancelMessage($announcement)
    {
        $title = $announcement->holiday ? 'Holiday Cancellation Notice' : 'Event Cancellation Notice: ';
        if ($announcement->holiday) {
            $date1 = Carbon::create($announcement->start);
            $date2 = Carbon::create($announcement->end);
            $days = $date1->diffInDays($date2);
            if ($days > 1) {
                $message = 'Unfortunately, ' . $announcement->title . ' holiday has been cancelled. We apologize for any disappointment and hope to see you at future events.';
            } else {
                $message = 'Unfortunately, ' . $announcement->title . ' holiday has been cancelled. We apologize for any disappointment and hope to see you at future events.';
            }
        } else {
            if ($announcement->allDay) {
                $date1 = Carbon::create($announcement->start);
                $date2 = Carbon::create($announcement->end);
                $days = $date1->diffInDays($date2);
                if ($days > 1) {
                    $message = 'Unfortunately, ' . $announcement->title . ' event has been cancelled. We apologize for any disappointment and hope to see you at future events.';
                } else {
                    $message = 'Unfortunately, ' . $announcement->title . ' event has been cancelled. We apologize for any disappointment and hope to see you at future events.';
                }
            } else {
                $message = 'Unfortunately, ' . $announcement->title . ' event has been cancelled. We apologize for any disappointment and hope to see you at future events.';
            }
        }
        $link = route('announcement.index');
        return (object) $announcement = ['title' => $title, 'message' => $message, 'link' => $link];
    }
}
