<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    public function index(){

        $categories = Categories::all()->sortByDesc('created_at');
       
        $formattedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        });
        return response()->json([
            'data' => $formattedCategories->values()
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
