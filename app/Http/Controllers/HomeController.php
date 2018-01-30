<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	public function index(Request $request) {
		$contents = DB::table('contents')->paginate(15);
		return view('home', ['contents' => $contents]);
	}

	public function mogi(Request $request, $id = 0) {
		$content = DB::table('contents')->where('id', '=', $id)->first();
		return view('mogi', ['content' => $content]);
	}
}