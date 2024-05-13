<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;


//授權csrf token
// Route::get('/csrf-token', function () {
//     return csrf_token();
// });

Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/post', [PostController::class, 'store']);
    Route::put('/post/{id}', [PostController::class, 'update']);
    Route::delete('/post/{id}', [PostController::class, 'destroy']);
});

#用get方法，導向「/post」的網址，並執行PostController的index方法，回傳頁面
// Route::get('/post', [PostController::class, 'index']);

// Route::get('/post/{id}', [PostController::class, 'show']);

// Route::post('/post', [PostController::class, 'store']);

// Route::put('/post/{id}', [PostController::class, 'update']);

// Route::delete('/post/{id}', [PostController::class,'destroy']);
// Route::resource('test', 'TestController');

//用resource方法，依據PostController內的每個function，各自建立一支符合RESTful API的路由 (GET、POST、PUT/PATCH、DELETE)，並以post作為路由前綴修飾，
// Route::resource('post', 'PostController');


Route::post('user',[AuthController::class,'login']);

//當用到名稱為login的路由時，會導向到AuthController的login方法
// Route::post('login', function () {
//     return redirect()->route('user');
// })->name('login');
