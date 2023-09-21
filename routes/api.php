<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Buyer\BuyerController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('test', function (Request $request) {
    $data = DB::table('attendance')
        ->select('*')
        ->get();
    return response()->json([
        'msg' => 'yes',
        'data' => $data
    ], 200);
});

Route::fallback(function () {
    return response()->json([
        'msg' => 'error',
        'code' => 404
    ], 404);
});





/*
 * buyers
 */
Route::resource('buyer', BuyerController::class)
    ->only(['index', 'show']);

/*
 * categories
 */
Route::resource('categories', \App\Http\Controllers\Category\CategoryController::class)
    ->except(['create', 'edit']);

/*
 * products
 */
Route::resource('products', \App\Http\Controllers\Product\ProductController::class)
    ->only(['index', 'show']);

/*
 * sellers
 */
Route::resource('sellers', \App\Http\Controllers\Seller\SellerController::class)
    ->only(['index', 'show']);

/*
 * transactions
 */
Route::resource('transactions', \App\Models\Transaction::class)
    ->only(['index', 'show']);

/*
 * users
 */
Route::resource('users', \App\Http\Controllers\User\UserController::class)
    ->except(['create', 'edit']);



# method #1
//Route::middleware(['auth'])
//    ->prefix('api')
//    ->group(function (){
//        Route::resource('buyer',BuyerController::class)
//            ->except(['create','update','destroy']);
//    });

# method #2
//Route::resource('buyer',BuyerController::class,[
//    'except' => ['create','update','destroy'],
//    'parameters' => ['buyer' => 'buyerID'],
//    'middleware' => ['auth'],
//    'prefix' => 'admin',
//]);
