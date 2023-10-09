<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});

Route::namespace('App\\Http\\Controllers\\API\V1')->group(function () {
    Route::get('profile', 'ProfileController@profile');
    Route::put('profile', 'ProfileController@updateProfile');
    Route::post('change-password', 'ProfileController@changePassword');
    Route::get('tag/list', 'TagController@list');
    Route::get('category/list', 'CategoryController@list');
    Route::post('product/upload', 'ProductController@upload');

    Route::apiResources([
        'user' => 'UserController',
        'product' => 'ProductController',
        'category' => 'CategoryController',
        'tag' => 'TagController',
    ]);
});

Route::namespace('App\\Http\\Controllers')->group(function () {
    Route::get('/cart-items', 'ShoppingCartController@index');
    Route::post('/cart-items', 'ShoppingCartController@create');
    Route::delete('/cart-items/{id}', 'ShoppingCartController@delete');
    Route::post('/complete-order', 'ShoppingCartController@update');

    Route::get('/orders', 'ViewOrdersController@index');
    Route::get('/view-order', 'ViewOrdersController@show');

    Route::post('/orders/{id}', 'InvoiceController@create');
    Route::get('/view-invoice', 'InvoiceController@show');
});


