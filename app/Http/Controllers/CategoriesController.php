<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $categories = Categories::find($id)->with('products.image')->get();
    
        return response()->json([
            "data"=>$categories
        ]);
    }
}
