<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// 認証モデルの使用
use Illuminate\Support\Facades\Auth;
// Image,Product,Owner,products,SecondaryCategoryモデルの使用
Use App\Models\Image;
use App\Models\Product;
use App\Models\Owner;
use App\Models\SecondaryCategory;
class ProductController extends Controller
{
    /* コンストラクタの設定 */
    public function __construct()
    {
        $this->middleware('auth:owners');

        // コントローラミドルウェア
        $this->middleware(function ($request, $next) {

            // 製品IDの取得
            $id = $request->route()->parameter('product');
            // null判定
            if(!is_null($id)){
                // shopに紐づくOwnerIdの取得
                $productsOwnerId= Product::findOrFail($id)->shop->owner->id;
                // 文字列→数値に変換
                $proudcutId = (int)$productsOwnerId;
                // 製品IDが認証済IDでない場合
                if($proudcutId  !== Auth::id()){
                    abort(404); // 404画面表示
                }
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // shopに紐づく製品の認証済IDの取得
        $products = Owner::findOrFail(Auth::id())->shop->product;

        // owner/products/index.balde.phpにproducts変数付で返す
        return view('owner.products.index',
        compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}