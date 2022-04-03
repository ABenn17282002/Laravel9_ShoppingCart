<?php

use Illuminate\Support\Facades\Route;

// ComponentTESTpage表示のためのクラス追加
use App\Http\Controllers\ComponentTestController;
// ServiceContainerクラスの追加
use App\Http\Controllers\LifeCycleTestController;
use GuzzleHttp\Middleware;
// ItemControllerクラスの追加
use App\Http\Controllers\User\ItemController;
// CartControllerクラスの追加
use App\Http\Controllers\User\CartController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('user.welcome');
});


/* User用ルート設定*/
Route::middleware('auth:users')->group(function(){
    // 商品一覧ページ用ルート
    Route::get('/', [ItemController::class, 'index'])->name('items.index');
    // 商品詳細ページ用ルート
    Route::get('show/{item}', [ItemController::class, 'show'])->name('items.show');
});

/* Cart用ルート設定 */
Route::prefix('cart')->
    middleware('auth:users')->group(function(){
        // Cart追加
        Route::post('add', [CartController::class, 'add'])->name('cart.add');
});

// ComponentTespage表示
Route::get('/component-test1',[ComponentTestController::class, 'showComponent1']);
Route::get('/component-test2',[ComponentTestController::class, 'showComponent2']);

// ServiceContainer表示
Route::get('/servicecontainertest',[LifeCycleTestController::class, 'showServiceContainerTest']);

// ServiceProvider表示
Route::get('/serviceprovidertest',[LifeCycleTestController::class, 'showServiceProviderTest']);

require __DIR__.'/auth.php';






