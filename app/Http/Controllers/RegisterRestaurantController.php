<?php

namespace App\Http\Controllers;

use App\Events\UserRoleChangedEvent;
use App\Models\Restaurant;
use App\Models\User;
use Exception;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Validation\ValidationException;
use Laracasts\Flash\Flash;
use Prettus\Validator\Exceptions\ValidatorException;

class RegisterRestaurantController extends Controller
{


    public function show(Request $request)
    {
        return view('register_restaurant');
    }


    /**
     * Store a newly created Restaurant in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            // auth
            'added_by' => 'required|string|min:3|max:64',

            // users data
            //'user_name' => 'required|min:3|max:100',
            'user_phone' => 'required|unique:users,phone_number',

            // restuarant data
            'name' => 'required|min:3|max:100',
            //'description' => 'nullable|max:100',
            'address' => 'nullable|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'required',
            'mobile' => 'nullable',
            //'information' => 'nullable|max:100',
            //'admin_commission' => 'required',
            //'delivery_fee' => 'required_if:delivery_price_type,fixed',
            //'deliver_range' => 'nullable|numeric',
            //'delivery_price_type' => 'required',
            //'default_tax' => 'required',
            //'active' => 'required',
            //'available_for_delivery' => 'required',
            //'private_drivers' => 'required',
        ]);




        $allowed_users = [
            '0001000',
            '0001001',
            '0001002',
            '0001003',
            '0001004',
            '0001005',
            '0001006',
            '0001007',
            '0001008',
            '0001009',
            '0001010',
            '0001011',
            '0001012',
            '0001013',
            '0001014',
            '0001015',
            '0001016',
            '0001017',
            '0001018',
            '0001019',
            '0001020',
        ];

        try {

            DB::beginTransaction();

            if (!in_array($request->added_by, $allowed_users)) {
                throw ValidationException::withMessages(['المستخدم غير صحيح']);
            }

            // restaurant data
            $restaurant = Restaurant::create(array_merge($data, [
                'private_drivers' => false,
                'delivery_price_type' => 'distance',
                'admin_commission' => 10,
                'deliver_range' => 20,
                'active' => true,
            ]));
            $user = $restaurant->users()->create([
                'name'  => $request->name, //$request->user_name,
                'phone_number'  => ltrim($request->user_phone, 0),
                'email' => now(),
                'password'  => Hash::make('123456'),
                'active' => true,
                'activated_at' => now(),
                'api_token'  => str_random(60),
            ]);

            $user->assignRole(['manager']);

            Log::channel('registerRestaurants')->info(json_encode([
                'restaurant' => $restaurant->toArray(),
                'user' => $user->toArray(),
                'request' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->server('HTTP_USER_AGENT')
            ]));
            DB::commit();
            Session::flash('success', 'تمت العملية بنجاح');
        } catch (ValidatorException $e) {
            DB::rollback();
            Flash::add($e->getMessage());
        }

        return redirect()->back();;
    }
}
