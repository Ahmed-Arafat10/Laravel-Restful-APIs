<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware(['guest']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::get('/test', function () {
    $category = \App\Models\Category::find(1);
    $categoriesLinks = [

    ];
    if (!Cache::get('sda'))
        echo "yes";
});


Route::get('/dashboard/clients', function (\Illuminate\Http\Request $request) {
    return view('clients', [
        'clients' => $request->user()->clients
    ]);
})->middleware(['auth'])->name('dashboard.clients');


Route::get('/hi', function () {
   $user =  \App\Models\User::create([
       'name' => 'ahmed ging',
       'email' => 'ahmedxarafat022221@gmail.com',
        'password' => \Illuminate\Support\Facades\Hash::make('123')
    ]);
    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
    return $token;
});


/*
eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNzRiYzNiYmY3OGIzMWY3MTJhODA3MDg5NTFjMjFhYTY0NDdjYjYyYzI1NDhlOWI0YjQ4NjBkNTkyMTkzYzU0YmUwYTc3YmFkZDUwYzI5MjgiLCJpYXQiOjE2OTYzMzE5MTQuNjY5MjE3LCJuYmYiOjE2OTYzMzE5MTQuNjY5MjE5LCJleHAiOjE3Mjc5NTQzMTQuNjY1MzczLCJzdWIiOiIxMTAiLCJzY29wZXMiOltdfQ.iG0-kM_9j3bW4v0F602A0kSsU-qqAKBuph_M70pWEBT9wey0rkWzybx_gL3Z3ZIkblcwR6o9MHCttbI8EROpH0jGqA1w_t95rByCmxWJReyZnRhameTR1ew6W-hviGQvdGd-cSDzl-QCzbSRxeAQZvp0GL35zrJoI57b8F5Skp_yp1lhbqaEsdCyFDQgCGOEgzcKgwcYv4y9DW9Wv2f-fP827ejpAbtlHb_28zRhILo7y0aLLwEV2avFnbz-aKwwjYgRkKdcIDcHWgMOAVOFqMNTMt6V1HuETy7Fd-kU5RCufQtApp0WF4ZQ83M0kNhJf7uzAzrLJtw_CJx08ch9k46SEqaWJPPzIpNc5R7zg3vTs2xb60MnaeibmaU192BOGudm152Zpu_IsA10RyZYaRLnUUMt0mCiltF5v2GZ5UP5nP2-tUjNIYsgbtjP2tTrfYA5v1CKrjwkhGWmwfyazz-mT7eVBAXgU2iclq_yqB4tHItdg1kGOqlTcdgRww7DYxbx4SAbtllBexp9inPX31CPKAf0LkpRbIBPjm23NAcBvX_qqp7jBohTcnesihCm3TNgaFpa081LUuRL1v1XlRwET8HwIfE_cO4z19arvsPdpW5vqhylLiouMlPWznjTkataNVW0ti-7XiRujXJ0pln_fHDbtihnfa8tCDnwi_E
 */
