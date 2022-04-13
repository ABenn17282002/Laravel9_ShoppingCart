<?php

namespace App\Services;
// Productモデルの使用
use App\Models\Product;
// Cartモデルの使用
use App\Models\Cart;

class CartService
{
    /* カートの商品を取得する関数 */
    public static function getItemsInCart($items){
        //空の配列を準備
        $products = [];

        // カート内の商品を一つずつ処理
        foreach($items as $item){

            /* Owner情報の取得 */
            // 製品ID取得
            $p = Product::findOrFail($item->product_id);
            // オーナー情報を取得し、配列化
            $owner = $p->shop->owner->select('name', 'email')->first()->toArray();
            //連想配列の値を取得
            $values = array_values($owner);
            // 連想配列のkeyを設定
            $keys = ['ownerName', 'email'];
            // オーナー情報を取得
            $ownerInfo = array_combine($keys, $values);
            // dd($ownerInfo);

            /* 商品・在庫情報の取得 */
            // 商品情報の配列化
            $product = Product::where('id', $item->product_id)
            ->select('id', 'name', 'price')->get()->toArray();
            // 在庫数の配列化
            $quantity = Cart::where('product_id', $item->product_id)
            ->select('quantity')->get()->toArray();
            // dd($ownerInfo, $product, $quantity);

            // 配列の結合
            $result = array_merge($product[0], $ownerInfo, $quantity[0]);
            // dd($result);

            // 取得したowner・商品情報等を配列に追加
            array_push($products, $result);
        }

        dd($products);
        return $products;
    }
}
