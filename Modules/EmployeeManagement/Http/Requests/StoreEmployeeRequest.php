<?php
namespace Modules\EmployeeManagement\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('Employee-create');
    }

    public function rules() :array
    {
        return [
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:employees,email', 'unique:users,email']
        ];
    }
}
