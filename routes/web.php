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
        // cart情報表示
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        // Cart追加
        Route::post('add', [CartController::class, 'add'])->name('cart.add');
        // Cartの削除
        Route::post('delete/{item}', [CartController::class, 'delete'])->name('cart.delete');
        // Stripe決済処理
        Route::get('checkout', [CartController::class,'checkout'])->name('cart.checkout');
        // Stripe決済成功時の処理
        Route::get('success', [CartController::class, 'success'])->name('cart.success');
        // Stripe決済失敗時の処理
        Route::get('cancel', [CartController::class, 'cancel'])->name('cart.cancel');
});

// ComponentTespage表示
Route::get('/component-test1',[ComponentTestController::class, 'showComponent1']);
Route::get('/component-test2',[ComponentTestController::class, 'showComponent2']);

// ServiceContainer表示
Route::get('/servicecontainertest',[LifeCycleTestController::class, 'showServiceContainerTest']);

// ServiceProvider表示
Route::get('/serviceprovidertest',[LifeCycleTestController::class, 'showServiceProviderTest']);

require __DIR__.'/auth.php';






