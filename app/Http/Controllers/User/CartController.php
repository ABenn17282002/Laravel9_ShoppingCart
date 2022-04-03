<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// Cartモデルの追加
use App\Models\Cart;
// Userモデルの追加
use App\Models\User;
// 認証モデルの追加
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // indexPage用メソッド
    public function index()
    {
        // userの取得
        $user = User::findOrFail(Auth::id());
        // user⇔productとの多対多リレーション
        $products = $user->products;
        // 総額表示
        $totalPrice = 0;

        // 製品を1つずつ取得
        foreach($products as $product){
            // 製品の価格 * 中間テーブルの数量の数
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        // dd($products, $totalPrice);

        return \view('user.cart',
        compact('products', 'totalPrice'));
    }


    // 商品追加のためのメソッド
    public function add(Request $request)
    {
        // Cartに商品があるかどうか
        $itemInCart = Cart::where('user_id',Auth::id())
        ->where('product_id',$request->product_id)->first();

        if($itemInCart){
            // cart内に商品がある場合、数量追加
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();

        }else{
            // cart内に商品がない場合、新規追加
            Cart::Create([
                'user_id'=> Auth::id(),
                'product_id'=>$request->product_id,
                'quantity'=>$request->quantity
            ]);
        }

        // dd('Test');
        // user.indexにリダイレクト
        return redirect()->route('user.cart.index');
    }
}
