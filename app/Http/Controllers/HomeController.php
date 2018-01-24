<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index(Request $request) {
		$contents = DB::table('contents')->paginate(15);
		return view('home', ['contents' => $contents]);
	}
}