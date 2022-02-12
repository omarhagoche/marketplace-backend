<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Driver;

class UpdateDriverRequest extends FormRequest
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
        /**
         * can't find way to ignore those values
         * unique email , unique phone_number 
         * the way that used searching drivers ids then get the id of the user 
         */

        $id = Driver::find($this->route('driver'))->user_id; //FIXME
        Driver::$rules['phone_number'] = 'unique:users,phone_number,' . $id;
        Driver::$rules['email'] = 'unique:users,email,' . $id;
        return Driver::$rules;
    }
}
