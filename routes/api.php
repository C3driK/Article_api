<?php

use App\Http\Controllers\ArticleController;
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

Route::get('/', function () {
    return response()->json(['success' => true, 'message' => 'Workwise Articles v1.0']);
});

Route::controller(ArticleController::class)->prefix('articles')->group(function () {
    Route::get('/', 'index')->name('article.index');
    Route::post('/', 'store')->name('article.store');
    Route::put('/{article:uuid}', 'update')->name('article.update');
    Route::delete('/{article:uuid}', 'delete')->name('article.delete');
});
