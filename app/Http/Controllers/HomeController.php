<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	public function index(Request $request) {
		$anchors = DB::table('anchors')->where('status', '=', 2)->paginate(15);
		return view('home', ['anchors' => $anchors]);
	}

	public function detail(Request $request, $anchor_id = 0) {
		$anchor = DB::table('anchors')->where('id', '=', $anchor_id)->first();
		$content = DB::table('contents')->where('anchor_id', '=', $anchor_id)->first();
		return view('detail', [
						'content' => $content,
						'anchor' => $anchor
					]);
	}
}