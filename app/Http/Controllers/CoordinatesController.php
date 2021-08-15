<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\coordinates;
use Carbon\Carbon;

class CoordinatesController extends Controller
{
    public function get(Request $request)
    {
        $items = Coordinates::get_all();
        return response()->json($items, 200);
    }

    public function post(Request $request)
    {
        $param = Coordinates::post_target($request);
        return response()->json($param, 200);
    }

    public function delete(Request $request)
    {
        Coordinates::where('id', $request->id)->delete();
        return response()->json(200);
    }
}
