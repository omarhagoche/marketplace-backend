<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user') ? $this->route('user') : $this->route('client');

        User::$rules['phone_number'] .= ',' . $id;
        User::$rules['email'] .= ',' . $id;
        User::$rules['password'] = str_replace('required', 'nullable', User::$rules['password']);

        return User::$rules;
    }
}
