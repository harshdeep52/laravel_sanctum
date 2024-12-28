<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('check.sanctum.token')->get('/user', function (Request $request) {
    return response()->json($request->user());
});


Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::middleware('check.sanctum.token')->group(function () {
    // Route::resource('products', ProductController::class);
    Route::post('addProducts', [ProductController::class, 'store']);
    Route::get('allProducts', [ProductController::class, 'index']);
    Route::get('deleteProducts', [ProductController::class, 'destroy']);
});
