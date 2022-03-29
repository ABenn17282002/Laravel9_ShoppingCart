<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
// Productクラスの使用
use App\Models\Product;
// DB Facades使用
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    // indexページの表示
    public function index()
    {
        // $products = Product::all();

        /* Stockの合計をグループ化->数量が1以上 */
        $stocks = DB::table('t_stocks')
        ->select('product_id',
        // select内でsumを使うためクエリビルダのDB::rawで対応
        DB::raw('sum(quantity) as quantity'))
        ->groupBy('product_id')
        ->having('quantity', '>', 1);

        $products = DB::table('products')
        // $stockのクエリーをSubqueryとして使用
        ->joinSub($stocks, 'stock', function($join){
        // Join product on products.id = stock.product_id
        $join->on('products.id', '=', 'stock.product_id');
        })
        // Join shops on products.shop_id = shops.id
        ->join('shops', 'products.shop_id', '=', 'shops.id')
        // where shops.is_selling =1
        ->where('shops.is_selling', true)
        // where products.is_selling =1
        ->where('products.is_selling', true)
        ->get();

        dd($stocks,$products);


        return view('user.index',\compact('products'));
    }
}
