<?php
namespace Modules\EventManagement\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->private && \in_array(auth()->user()->roles[0]->name, explode(',', $this->viewer))) {
            return
            [
                'id' => $this->id,
                'title' => $this->title,
                'detail' => $this->detail,
                'start' => $this->start,
                'end' => $this->end,
                'allDay' => $this->allDay,
                'holiday' => $this->holiday,
                'private' => $this->private,
                'notify' => $this->notify,
                'viewer' => $this->viewer,
            ];
        } elseif (!$this->private) {
            return
            [
                'id' => $this->id,
                'title' => $this->title,
                'detail' => $this->detail,
                'start' => $this->start,
                'end' => $this->end,
                'allDay' => $this->allDay,
                'holiday' => $this->holiday,
                'private' => $this->private,
                'notify' => $this->notify,
                'viewer' => $this->viewer,
            ];
        }
        return[];
    }
}
