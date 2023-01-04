<?php
namespace Modules\StaffManagement\Http\Requests;

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
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'email' => [
                'required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:staffs,email,' . request()->id
            ],
        ];
    }
}
