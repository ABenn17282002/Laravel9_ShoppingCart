<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    /* カートの商品を取得する関数 */
    public static function getItemsInCart($items){
        //空の配列を準備
        $products = [];

        dd($items);

        // カート内の商品を一つずつ処理
        foreach($items as $item){
        }

        return $products;
    }
}
