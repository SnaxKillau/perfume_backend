<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            "availableUnit" => $request->availableUnit,
            "decription" => $request->decription
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
    public function show($id){
        $products = Products::with('image', 'brands')->find($id);
    
    
        $products['brand'] = $products->brands->name;
        
        return response()->json([
            "data"=>$products
        ]);
    }
    public function addToBag($id)
{
    // Get the authenticated user
    $loggedInUser = auth()->user();

    // Find the product with the 'image' and 'brands' relationships loaded
    $product = Products::with('image', 'brands')->findOrFail($id);

    if ($loggedInUser && $product) {
        // Attach the product to the user
        $data = $loggedInUser->products()->attach($product->id);

        // Retrieve the user's products with 'image' and 'brands' relationships loaded
        $groupedProducts = $loggedInUser->products()->with('image', 'brands')->get();

        $uniqueProducts = [];
        $count = [];

        foreach ($groupedProducts as $groupedProduct) {
            $currentProductId = $groupedProduct->id;

            if (!isset($count[$currentProductId])) {
                // First occurrence, add to uniqueProducts and initialize count
                $uniqueProducts[] = $groupedProduct;
                $count[$currentProductId] = 1;
            } else {
                // Duplicate occurrence, increment count
                $count[$currentProductId]++;
            }
        }

        // Add count to each unique product
        foreach ($uniqueProducts as $uniqueProduct) {
            $uniqueProduct["count"] = $count[$uniqueProduct->id];
        }
    } else {
        return response()->json(["error" => "User or Product not found!"], 404);
    }

    return response()->json([
        "data" => $uniqueProducts
    ]);
}
public function bag(){
    $loggedInUser = auth()->user();
    
    // Retrieve the user's products with 'image' and 'brands' relationships loaded
    $groupedProducts = $loggedInUser->products()->with('image', 'brands')->get();

    $uniqueProducts = [];
    $count = [];

    foreach ($groupedProducts as $groupedProduct) {
        $currentProductId = $groupedProduct->id;

        if (!isset($count[$currentProductId])) {
            // First occurrence, add to uniqueProducts and initialize count
            $uniqueProducts[] = $groupedProduct;
            $count[$currentProductId] = 1;
        } else {
            // Duplicate occurrence, increment count
            $count[$currentProductId]++;
        }
    }

    // Add count to each unique product
    foreach ($uniqueProducts as $uniqueProduct) {
        $uniqueProduct["count"] = $count[$uniqueProduct->id];
    }
    return response()->json([
        "data" => $uniqueProducts
    ]);
    
}
public function payment(){
    $loggedInUser = auth()->user();
    $loggedInUser->products()->detach();
    return response()->json([
        "data"=>[]
    ]);

}
   
}
