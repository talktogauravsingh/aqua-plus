<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::post('login', [AdminController::class, 'index'])->name('login');

    /**
     * @create customer details
     * @Read customer details
     * @update customer details
     * @delete customer details
     */

    Route::resource('customer', CustomerController::class);

    // Retrive users who had completed 3 month journey
    Route::get('notify', [CustomerController::class, 'notify']);

});
