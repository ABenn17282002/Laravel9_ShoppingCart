<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
// Productクラスの使用
use App\Models\Product;
// Stockクラスの使用
use App\Models\Stock;
// PrimaryCategoryクラスの使用
use App\Models\PrimaryCategory;
// DB Facades使用
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// メール送信用クラスの使用
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
// 非同期処理用クラス設定
use App\Jobs\SendThanksMail;

class ItemController extends Controller
{
    /* コンストラクタの設定 */
    public function __construct()
    {

        $this->middleware('auth:users');

        // コントローラミドルウェア
        $this->middleware(function ($request, $next) {

            // ItemIDの取得
            $id = $request->route()->parameter('item');
            // null判定
            if(!is_null($id)){
            // ItemIDが存在しているかどうかの判定
            $itemId = Product::availableItems()->where('products.id', $id)->exists();
                // ItemIdがなければ
                if(!$itemId){
                    abort(404); // 404画面表示
                }
            }
                return $next($request);
            });
    }


    // indexページの表示(引数:Request $request)
    public function index(Request $request)
    {
        // dd($request);

        // 同期処理
        // Mail::to('test@example.com')
        // ->send(new TestMail());

        // キューにジョブを入れて処理(非同期)
        // SendThanksMail::dispatch();

        // withを用いて、関連するsecondaryも一緒に取得する.
        $categories = PrimaryCategory::with('secondary')
        ->get();

        /* LocalScope */
        $products = Product::availableItems()
        // カテゴリー検索
        ->selectCategory($request->category ?? '0')
        // キーワード検索
        -> searchKeyword($request->keyword)
        // 商品情報の表示順を取得
        ->sortOrder($request->sort)
        // Pagination(初期設定:20件)
        ->paginate($request->pagination ?? '20');

        return view('user.index',\compact('products','categories'));
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
