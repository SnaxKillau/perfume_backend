<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(){
        $products = Products::with(["image" , "brands"])->get();
        
        foreach($products as $product){
            $product['brand'] = $product->brands->name;
        }
        return response()->json([
            "data"=>$products
        ]);
    }
    public function create (Request $request) {
        $product  = Products::create([
            "name"=> $request->name,
            "type"=> $request->type,
            "price" => $request->price,
            "brands_id"=> $request->brands_id,
            "categories_id"=> $request->categories_id,
            "availableUnit" => $request->availableUnit
        ]);
        if ($request->hasFile('image')){
            $image = $request->file('image') ;
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images') ;
            $image->move($destinationPath, $name) ;
            $data = $name;
            $baseUrlImage = request()->getSchemeAndHttpHost()."/images";
            $imageURL = $baseUrlImage.'/'.$data;    
         }
         else{
            return response()->json([
                "message" => "File is empty"
            ]);
         }
         $img = Images::create([
            "products_id" => $product->id,
            "url" => $data
         ]);
         
        return response()->json([
            "data"=>$product
        ]);
    }
}
