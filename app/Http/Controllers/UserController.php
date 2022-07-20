<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Helper::jsonData(User::with('roles')->orderByDesc('id')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'exists:roles,name'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole($request->role);
            return Helper::jsonData($user, 201);
        } catch (QueryException $e) {
            return Helper::errorJson();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            return Helper::jsonData($user);
        } catch (QueryException $e) {
            return Helper::jsonNotFound();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'unique:users,email,' . $id],
            'role' => ['required', 'exists:roles,name']
        ];

        $data = [];

        if ($request->password) {
            $rules['password'] = ['confirmed', 'min:8'];
            $data['password'] = bcrypt($request->password);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        try {
            $user = User::with('roles')->findOrFail($id);
            $data['email'] = $request->email;
            $data['name'] = $request->name;
            $user->update($data);
            $user->syncRoles($request->role);
            return Helper::jsonData($user);
        } catch (QueryException $e) {
            return Helper::jsonNotFound();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return Helper::jsonData($user);
        } catch (QueryException $e) {
            return Helper::jsonNotFound();
        }
    }

    public function deleteSelf(Request $request)
    {
        $id = $request->user()->id;

        try {
            $user = User::findOrFail($id);
            $user->delete();
            return Helper::jsonData($user);
        } catch (QueryException $e) {
            return Helper::jsonNotFound();
        }
    }
}
