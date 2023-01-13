<?php
namespace Modules\EventManagement\Services;

use Modules\EventManagement\Models\Announcement;
use Illuminate\Database\QueryException;

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
}
