<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("/register" , [AuthController::class, 'register']);

Route::post("/login" , [AuthController::class, 'login']);

Route::post("/logout" , [AuthController::class, 'logout']);

Route::middleware(['auth:api'])->group(function(){

    Route::get("/category" , [CategoriesController::class , 'index']);
    Route::post("/category" , [CategoriesController::class , 'create']);
    Route::get("/brand" , [BrandsController::class , 'index']);
    Route::post("/brand" , [BrandsController::class , 'create']);
    Route::get("/product" , [ProductsController::class , 'index']);
    Route::post("/product" , [ProductsController::class , 'create']);
    Route::get("/productCategory/{id}" , [CategoriesController::class , 'products']);
    Route::get("/product/{id}" , [ProductsController::class , 'show']);
    Route::post("/addToBag/{id}" , [ProductsController::class , 'addToBag']);
    Route::get("/bag", [ProductsController::class , "bag"]);
    Route::delete("/pay" , [ProductsController::class , "payment"]);
    Route::get("/profile" , [AuthController::class  , "profile"]);
    Route::post("/updateProfile" , [AuthController::class , "update"]);
    Route::post("/logout" , [AuthController::class , "logout"]);
});

 


