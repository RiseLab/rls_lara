<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        return Category::select('id', 'title', 'alias')
	        ->get();
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
		    'title' => 'required|max:80|min:2',
	    ]);
	    if ($validator->fails()){
		    return response()->json(['message' => $validator->errors()->first('title')], 400);
	    }
	    $category = Category::create([
		    'title' => $request->title,
		    'alias' => Str::slug($request->title)
	    ]);
	    $category->save();
	    return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
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
	    	'title' => 'required|max:80|min:2'
	    ]);
	    if ($validator->fails()){
		    return response()->json(['message' => $validator->errors()->first('title')], 400);
	    }
	    $category = Category::where('id', $id)->first();
	    $category->title = $request->title;
	    $category->alias = Str::slug($request->title);
		$category->save();
	    return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $category = Category::where('id', $id)->first();
	    if (!is_null($category)){
		    $category->delete();
		    return response()->json(null, 204);
	    } else {
		    return response()->json(['message' => 'Category not found.'], 400);
	    }
    }
}
