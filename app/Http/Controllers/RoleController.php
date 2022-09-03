<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Helper::jsonData(Role::with('permissions')->orderByDesc('id')->paginate());
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
            'name' => ['required', 'regex:/^\S*$/u', 'unique:roles,name'],
            'permissions' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        try {
            $role = Role::create(['name' => Str::lower($request->name), 'guard_name' => 'web']);
            $permissions = $request->permissions;
            $role->syncPermissions($permissions);
            return Helper::jsonData($role, 201);
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
            $role = Role::findOrFail($id);
            return Helper::jsonData($role);
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'regex:/^\S*$/u', 'unique:roles,name,' . $id],
            'permissions' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        try {
            $role = Role::findOrFail($id);
            $role->update(['name' => Str::lower($request->name)]);
            $permissions = $request->permissions;
            $role->syncPermissions($permissions);
            return Helper::jsonData($role);
        } catch (QueryException $e) {
            return Helper::errorJson();
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
            $role = Role::findOrFail($id);
            $role->delete();
            return Helper::jsonData($role);
        } catch (QueryException $e) {
            return Helper::jsonNotFound();
        }
    }
}
