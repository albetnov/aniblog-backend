<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TokenController extends Controller
{
    public function issue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => 'required',
            'device_name' => 'required'
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return Helper::jsonNotFound(['message' => 'The provided cresidentials do not match records.']);
        }

        return Helper::jsonData(['message' => 'Authentication successful', 'token' => $user->createToken($request->device_name)->plainTextToken]);
    }

    public function revoke(Request $request)
    {
        $attempt = $request->user()->currentAccessToken()->delete();

        if (!$attempt) {
            return Helper::jsonNotFound("Token not found.");
        }

        return Helper::jsonData(['message' => 'Token revoked successfully.']);
    }

    public function newUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users'],
            'name' => 'required',
            'password' => ['required', 'min:8', 'confirmed'],
            'device_name' => 'required'
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        $user = User::create($validator->getData())->assignRole('user');

        if (!$user) {
            return Helper::errorJson();
        }

        return Helper::jsonData(['message' => 'Registered successfully.', 'user' => $user, 'token' => $user->createToken($request->device_name)->plainTextToken]);
    }

    public function editUser(Request $request)
    {
        $userId = $request->user()->id;

        $rules = [
            'email' => ['required', 'email', 'unique:users,email,' . $userId],
            'name' => 'required',
        ];

        $data = [
            'email' => $request->email,
            'name' => $request->name,
        ];

        if ($request->password) {
            $rules['password'] = ['required', 'min:8', 'confirmed'];
            $data['password'] = bcrypt($request->password);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        $user = User::where('id', $userId)->update($data);

        if (!$user) {
            return Helper::errorJson();
        }

        return Helper::jsonData(['message' => "Updated successfully!"]);
    }
}
