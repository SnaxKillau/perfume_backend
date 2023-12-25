<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    public function index(){
        $brand = Brands::all()->sortByDesc('created_at');

        return response()->json([
            "data"=>$brand
        ]);
    }
    public function create (Request $request) {
        $brand  = Brands::create([
            "name"=> $request->name
        ]);
        return response()->json([
            "data"=>$brand
        ]);
    }
}
