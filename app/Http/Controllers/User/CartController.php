<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Cart・認証モデルの追加
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
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

        dd('Test');
    }
}
