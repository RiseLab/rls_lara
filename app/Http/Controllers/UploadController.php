<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;

class UploadController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if ($request->hasFile('file')) {
			$path = storage_path('app/public/' . $request->file('file')->store('temp', 'public'));
			$image = Image::make($path)->fit(1024, 768)->save($path);
			$imageName = $image->filename . '.' . $image->extension;
			return response()->json($imageName, 201);
		} else {
			return response()->json(['error' => 'Upload error'], 400);
		}
	}
}
