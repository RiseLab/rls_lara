<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::select('id', 'title', 'alias')
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
	    $product = Product::create([
		    'title' => $request->title,
		    'alias' => Str::slug($request->title)
	    ]);
	    $product->save();
	    return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
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
	    $product = Product::where('id', $id)->first();
	    $product->title = $request->title;
	    $product->alias = Str::slug($request->title);
		$product->save();
	    return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $product = Product::where('id', $id)->first();
	    if (!is_null($product)){
		    $product->delete();
		    return response()->json(null, 204);
	    } else {
		    return response()->json(['message' => 'Product not found.'], 400);
	    }
    }
}
