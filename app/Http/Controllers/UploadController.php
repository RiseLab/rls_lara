<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
			$path = $request->file('file')->store('temp', 'uploads');
			return response()->json($path, 201);
		} else {
			return response()->json(['error' => 'Upload error'], 400);
		}
	}
}
