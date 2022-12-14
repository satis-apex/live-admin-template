<?php
namespace Modules\Staff\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('Staff-edit');
    }

    public function rules()
    {
        return [
            'email' => [
                'required', 'string', 'email', 'max:255', 'unique:staffs,email,' . request()->id
            ],
        ];
    }
}
