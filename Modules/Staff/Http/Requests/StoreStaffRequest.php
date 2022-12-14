<?php
namespace Modules\Staff\Http\Requests;

use Modules\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('Staff-create');
    }

    public function rules() :array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class]
        ];
    }
}
