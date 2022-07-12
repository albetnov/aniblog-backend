<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Helper::jsonData(Category::orderByDesc('id')->paginate());
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
            'name' => ['required', 'unique:categories'],
            'details' => ['required']
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        try {
            $category = Category::create($request->all());
            return Helper::jsonData($category, 201);
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
            $category = Category::findOrFail($id);
            return Helper::jsonData($category);
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
            'name' => ['required', 'unique:categories,name,' . $id],
            'details' => ['required']
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        try {
            $category = Category::findOrFail($id);
            $category->update($request->all());
            return Helper::jsonData($category);
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
            $category = Category::findOrFail($id);
            $category->delete();
            return Helper::jsonData($category);
        } catch (QueryException $e) {
            return Helper::jsonNotFound();
        }
    }
}
