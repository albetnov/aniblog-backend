<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Helper::jsonData(Blog::with('categories')->orderByDesc('id')->paginate());
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
            'title' => ['required'],
            'content' => ['required'],
            'categories' => ['required']
        ]);


        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        try {
            return DB::transaction(function () use ($request) {
                $blog = Blog::with('categories')->create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'posted_by' => $request->user()->id
                ]);
                $categories = Helper::parseArrayString($request->categories);
                Category::findOrFail($categories)->each(function ($category) use ($blog) {
                    $blog->categories()->attach($category);
                });
                return Helper::jsonData($blog, 201);
            });
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
            $blog = Blog::with('categories')->findOrFail($id);
            return Helper::jsonData($blog);
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
            'title' => ['required'],
            'content' => ['required'],
            'categories' => ['required']
        ]);

        if ($validator->fails()) {
            return Helper::jsonValidation($validator);
        }

        $request->posted_by = $request->user()->id;

        try {
            return DB::transaction(function () use ($request, $id) {
                $blog = Blog::with('categories')->findOrFail($id);
                $blog->update($request->except('categories'));
                $categories = Helper::parseArrayString($request->categories);
                $blog->categories()->sync($categories);
                return Helper::jsonData($blog);
            });
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
            $blog = Blog::with('categories')->findOrFail($id);
            $blog->delete();
            return Helper::jsonData($blog);
        } catch (QueryException $e) {
            return Helper::jsonNotFound();
        }
    }
}
