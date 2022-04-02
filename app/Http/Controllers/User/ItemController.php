<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
// Productクラスの使用
use App\Models\Product;
// Stockクラスの使用
use App\Models\Stock;
// DB Facades使用
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /* コンストラクタの設定 */
    public function __construct()
    {
        $this->middleware('auth:users');
    }


    // indexページの表示
    public function index()
    {

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
        // Eloquenからクエリービルダーに変更したため、tableの紐づくidを同士を結ぶ
        ->join('secondary_categories', 'products.secondary_category_id', '=',
        'secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')
        ->join('images as image2', 'products.image2', '=', 'image2.id')
        ->join('images as image3', 'products.image3', '=', 'image3.id')
        ->join('images as image4', 'products.image4', '=', 'image4.id')
        // where shops.is_selling =1
        ->where('shops.is_selling', true)
        // where products.is_selling =1
        ->where('products.is_selling', true)
        // select products.id,name,price,sort_order,information,
        // secondary_categories.name,image1.filename
        ->select('products.id as id', 'products.name as name', 'products.price'
        ,'products.sort_order as sort_order'
        ,'products.information', 'secondary_categories.name as category'
        ,'image1.filename as filename')
        ->get();

        // dd($stocks,$products);


        return view('user.index',\compact('products'));
    }

    // 商品詳細ページ
    public function show($id)
    {
        // 製品IDがあれば情報取得,ない場合Not Found
        $product = Product::findorFail($id);
        // 在庫数量の取得
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        // 数量の最大値を9に設定
        if($quantity > 9){
            $quantity =9;
        }

        return \view('user.show',\compact('product','quantity'));
    }
}
