<?php

/**
 * File name: UserAPIController.php
 * Last modified: 2020.08.11 at 23:04:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API\Manager;

use App\Criteria\Users\DriversOfRestaurantCriteria;
use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Rules\PhoneNumber;
use App\Models\VerficationCode;

class UserAPIController extends Controller
{
    /**
     * @var UserRepository
     */
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
            if (auth()->attempt(['phone_number' => $request->input('phone_number'), 'password' => $request->input('password')])) {
                // Authentication passed...
                $user = auth()->user();
                if (!$user->activated_at) {
                    return $this->sendError('Inactivated account', 401);
                }
                if (!$user->active) {
                    return $this->sendError('Disabled account', 403);
                }
                if (!$user->hasRole('manager')) {
                    $this->sendError('User not driver', 401);
                }
                $user->device_token = $request->input('device_token', '');
                $user->save();
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
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
        $this->validate($request, [
            'token' => 'required|string|min:64|max:256',
            'name' => 'required|min:3|max:32',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:32',
        ]);

        $user = new User;
        \DB::transaction(function () use ($request, $user) {
            $verfication = VerficationCode::where('token', $request->token)->firstOrFail();
            $user->name = $request->input('name');
            $user->phone_number =    $verfication->phone;
            $user->email = $request->input('email');
            $user->device_token = $request->input('device_token', '');
            $user->password = Hash::make($request->input('password'));
            $user->api_token = str_random(60);
            $user->save();
            $verfication->delete();

            $user->assignRole(['manager']);

            event(new UserRoleChangedEvent($user));
        });

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            auth()->logout();
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user['name'], 'User logout successfully');
    }

    function user(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();

        if (!$user) {
            return $this->sendError('User not found', 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key(
            $settings,
            [
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
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
            ]
        );

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
                $user = $this->userRepository->update($request->only('device_token'), $id);
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

    /**
     * Display a listing of the Drivers.
     * GET|HEAD /restaurants
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function driversOfRestaurant($id, Request $request)
    {
        try {
            $this->userRepository->pushCriteria(new RequestCriteria($request));
            $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->userRepository->pushCriteria(new DriversOfRestaurantCriteria($id));
            $users = $this->userRepository->all();
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($users->toArray(), 'Drivers retrieved successfully');
    }
}
