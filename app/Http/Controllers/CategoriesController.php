<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(){
        $categories = Categories::all()->sortByDesc('created_at');

        return response()->json([
            "data"=>$categories
        ]);
    }
    public function create (Request $request) {
        $category  = Categories::create([
            "name"=> $request->name
        ]);
        return response()->json([
            "data"=>$category
        ]);
    }
    public function products ($id){
        $category = Categories::find($id)->with('products')->get();
        return response()->json([
            "data"=>$category
        ]);
    }
}
