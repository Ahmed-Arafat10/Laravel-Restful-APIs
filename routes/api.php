<?php

use App\Http\Controllers\Buyer\BuyerCategoryController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Buyer\BuyerProductController;
use App\Http\Controllers\Buyer\BuyerSellerController;
use App\Http\Controllers\Buyer\BuyerTransactionController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Category\CategoryTransactionController;
use App\Http\Controllers\Product\ProductBuyerController;
use App\Http\Controllers\Product\ProductBuyerTransactionController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductTransactionController;
use App\Http\Controllers\Seller\SellerBuyerController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerTransactionController;
use App\Http\Controllers\Transaction\TransactionCategoryController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Transaction\TransactionSellerController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
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

//Route::fallback(function () {
//    return response()->json([
//        'msg' => 'error',
//        'code' => 404
//    ], 404);
//});


# buyers
Route::resource('buyers', BuyerController::class)
    ->only(['index', 'show']);
Route::resource('buyers.transactions', BuyerTransactionController::class)
    ->only(['index']);
Route::resource('buyers.products', BuyerProductController::class)
    ->only(['index']);
Route::resource('buyers.sellers', BuyerSellerController::class)
    ->only(['index']);
Route::resource('buyers.categories', BuyerCategoryController::class)
    ->only(['index']);


# categories
Route::resource('categories', CategoryController::class)
    ->except(['create', 'edit']);
Route::resource('categories.products', CategoryProductController::class)
    ->only(['index']);
Route::resource('categories.sellers', CategorySellerController::class)
    ->only(['index']);
Route::resource('categories.transactions', CategoryTransactionController::class)
    ->only(['index']); // danger
Route::resource('categories.buyers', CategoryBuyerController::class)
    ->only(['index']);  // danger: search for it

# products
Route::resource('products', ProductController::class)
    ->only(['index', 'show']);
Route::resource('products.transactions', ProductTransactionController::class)
    ->only(['index']);
Route::resource('products.buyers', ProductBuyerController::class)
    ->only(['index']);
Route::resource('products.categories', ProductCategoryController::class)
    ->only(['index', 'update', 'destroy']);
Route::resource('products.buyers.transactions', ProductBuyerTransactionController::class)
    ->only(['store']);

# sellers
Route::resource('sellers', SellerController::class)
    ->only(['index', 'show']);
Route::resource('sellers.transactions', SellerTransactionController::class)
    ->only(['index']);
Route::resource('sellers.categories', SellerCategoryController::class)
    ->only(['index']);
Route::resource('sellers.buyers', SellerBuyerController::class)
    ->only(['index']);
Route::resource('sellers.products', SellerProductController::class)
    ->except(['create', 'show', 'edit']);

# transactions
Route::resource('transactions', TransactionController::class)
    ->only(['index', 'show']);
Route::resource('transactions.categories', TransactionCategoryController::class)
    ->only(['index']);
Route::resource('transactions.sellers', TransactionSellerController::class)
    ->only(['index']);
/*
 * users
 */
Route::resource('users', UserController::class)
    ->except(['create', 'edit']);


# now it will use middlewares -> signature/throttle/binding as it is defined in api.php (was defined in kernal.php)
Route::post('oauth/token', [AccessTokenController::class, 'issueToken']);

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
