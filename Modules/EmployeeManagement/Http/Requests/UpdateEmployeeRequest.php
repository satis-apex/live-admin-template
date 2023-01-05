<?php
namespace Modules\EmployeeManagement\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('Employee-edit');
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'email' => [
                'required', 'string', 'email', 'max:255', 'unique:users,email,' . request()->account_id, 'unique:employees,email,' . request()->id
            ],
        ];
    }
}
