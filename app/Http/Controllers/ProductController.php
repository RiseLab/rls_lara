<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        return Product::select('id', 'category_id AS category', 'brand', 'title', 'alias', 'source_link AS sourceLink', 'info', 'description', 'photos', 'price', 'price_old AS priceOld', 'stock', 'available')
	        ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
	    $validator = Validator::make(request()->all(), [
		    'category' => 'required|numeric',
		    'brand' => 'present',
	    	'title' => 'required|max:40|min:2',
		    'sourceLink' => 'present',
		    'info' => 'required|min:2',
		    'description' => 'required|min:2',
		    'photos' => 'present',
		    'price' => 'required|numeric',
		    'priceOld' => 'required|numeric',
		    'stock' => 'required|numeric',
		    'available' => 'required|boolean',
	    ]);
	    if ($validator->fails()){
		    return response()->json(['message' => $validator->errors()->first()], 400);
	    }
	    try {
	    	$product = Product::create([
			    'category_id' => request()->category,
			    'brand' => request()->brand,
			    'title' => request()->title,
			    'alias' => Str::slug(request()->title),
			    'source_link' => request()->sourceLink,
			    'info' => request()->info,
			    'description' => request()->description,
			    'photos' => '[]',
			    'price' => request()->price,
			    'price_old' => request()->priceOld,
			    'stock' => request()->stock,
			    'available' => request()->available,
		    ]);
		    $product->save();

		    $photos = request('photos');

			foreach ($photos as $id => &$photo) {
				$name = \File::name($photo['path']);
				$ext = \File::extension($photo['path']);
				$photo['path'] = '/storage/uploads/products/' . $product->id . '/' . $product->alias . '-' . ($id + 1) . '.' . $ext;
				Storage::disk('public')
					->move('temp/' . $name . '.' . $ext, str_replace('/storage/', '', $photo['path']));
			}
			unset($photo);

			$product->photos = json_encode($photos);
			$product->save();

		    return response()->json($product, 201);
	    } catch (\Exception $e) {
		    return response()->json(['message' => $e->getMessage()], 400);
	    }
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
    	try {
	        $product = Product::where('id', $id)->first();
	        $product->delete();
		    Storage::disk('public')->deleteDirectory('uploads/products/' . $id);
		    return response()->json(null, 204);
	    } catch (\Exception $e) {
		    return response()->json(['message' => $e->getMessage()], 400);
	    }
    }
}
