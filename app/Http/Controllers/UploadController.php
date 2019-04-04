<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class UploadController extends Controller
{
	/**
	 * Store a newly created file in storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{
		// TODO: validation
		if (request()->hasFile('file')) {
			$path = storage_path('app/public/' . request()->file('file')->store(request('path'), 'public'));
			$image = Image::make($path)->fit(1024, 768)->save($path);
			$imageName = $image->filename . '.' . $image->extension;
			return response()->json($imageName, 201);
		} else {
			return response()->json(['error' => 'Upload error'], 400);
		}
	}

	/**
	 * Remove the specified file.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy()
	{
		try {
			Storage::disk('public')->delete(request('path'));
			return response()->json(null, 204);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}
}
