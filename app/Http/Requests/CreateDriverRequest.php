<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Driver;
use Kreait\Firebase\Request\CreateUser;

class CreateDriverRequest extends FormRequest
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
        $user_rules = new CreateUserRequest();
        $user_rules = $user_rules->rules();
        $user_rules['password'] .= '|confirmed';
        $rules = array_merge(Driver::$rules,  $user_rules);
        return  $rules;
        return [];
    }
}
