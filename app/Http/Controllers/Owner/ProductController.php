<?php

namespace App\Http\Controllers\Owner;

use App\Models\Shop;
use App\Models\Owner;
// 認証モデルの使用
use App\Models\Stock;
// DBFacadeの使用
use App\Models\Product;
// Image,Product,Owner,productsモデルの使用
Use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\PrimaryCategory;
// PrimaryCategoryに修正
use Illuminate\Support\Facades\DB;
// shopモデルの使用を追加
use App\Http\Controllers\Controller;
// Stockモデルの使用
use Illuminate\Support\Facades\Auth;
// productRequestクラスの使用
use App\Http\Requests\ProductRequest;

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
        // EagerLoadingなし
        // $products = Owner::findOrFail(Auth::id())->shop->product;

        /*N + 1問題の対策:リレーション先のリレーション情報を取得
        → withメソッド、リレーションをドットでつなぐ*/
        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id', Auth::id())->get();

        // owner/products/index.balde.phpにproducts変数付で返す
        return view('owner.products.index',
        compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // shops_tableよりid,nameを取得
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        // Images_tableよりid,title,filenameを更新順に取得
        $images = Image::where('owner_id', Auth::id())
        ->select('id','title','filename')
        ->orderBy('updated_at','desc')
        ->get();

        // withを用いて、関連するsecondaryも一緒に取得する.
        $categories = PrimaryCategory::with('secondary')
        ->get();

        // owner/products/create.balde.phpに上記変数付で返す
        return \view('owner.products.create',
        \compact('shops','images','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        // dd($request);

        // try catch構文
        try {
            // transaction2回失敗時=> error(引数:$request)
            DB::transaction(function() use($request){

            /**
             * Prouduct情報作成時にStock情報も同時作成*/
            // 店舗名、店舗情報、価格等を登録
            $product = Product::create([
                'name' => $request->name,
                'information' => $request->information,
                'price' => $request->price,
                'sort_order' => $request->sort_order,
                'shop_id' => $request->shop_id,
                'secondary_category_id' => $request->category,
                'image1' => $request->image1,
                'image2' => $request->image2,
                'image3' => $request->image3,
                'image4' => $request->image4,
                'is_selling' => $request->is_selling
            ]);

            // 在庫情報作成
            Stock::create([
                // Product_tableより商品idを取得
                'product_id' => $product->id,
                // 入庫・在庫を増やす場合は1とする。
                'type' => 1,
                // 数量
                'quantity' => $request->quantity
            ]);

            },2);

        }catch(Throwable $e){
            // 例外処理の記録と画面表示
            Log::error($e);
            throw $e;
        }

        // owners.products.indexページへリダイレクト flashmessage
        return \redirect()->route('owner.products.index')
        ->with('info','商品登録が完了しました。');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // productidの取得
        $product = Product::findOrFail($id);
        // 製品数量を合計する
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        // shops_tableよりid,nameを取得
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        // Images_tableよりid,title,filenameを更新順に取得
        $images = Image::where('owner_id', Auth::id())
        ->select('id','title','filename')
        ->orderBy('updated_at','desc')
        ->get();

        // withを用いて、関連するsecondaryも一緒に取得する.
        $categories = PrimaryCategory::with('secondary')
        ->get();

        // 上記変数をowner/products/edit.blade.phpに渡す
        return \view('owner.products.edit',
        \compact('product', 'quantity', 'shops',
        'images', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ProductRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        // validatation
        $request->validate([
            'current_quantity' => ['required', 'integer'],
        ]);

        // productidの取得
        $product = Product::findOrFail($id);
        // 製品数量を合計する
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        // 画面表示上の在庫数と違っている場合
        if($request->current_quantity !== $quantity){
            // ルートパラメータの取得
            $id = $request->route()->parameter('product');
            // redirect to owner/products/edit.blade.php+ flassmessage
            return redirect()->route('owner.products.edit', ['product' => $id])
            ->with('alert','在庫数が変更されています。再度確認してください');

        }else{
            // そうでなければ製品情報と在庫情報同時更新
            // トランザクション
            try{
                DB::transaction(function () use($request, $product) {

                    /* 製品情報更新処理  */
                    // idを元に取得したProduct情報から商品名等を取得
                    $product->name = $request->name;
                    $product->information = $request->information;
                    $product->price = $request->price;
                    $product->sort_order = $request->sort_order;
                    $product->shop_id = $request->shop_id;
                    $product->secondary_category_id = $request->category;
                    $product->image1 = $request->image1;
                    $product->image2 = $request->image2;
                    $product->image3 = $request->image3;
                    $product->image4 = $request->image4;
                    $product->is_selling = $request->is_selling;

                    // 情報を保存(Createがないため必要)
                    $product->save();

                    // 在庫追加処理
                    if($request->type === \Constant::PRODUCT_LIST['add'])
                    {
                        $newQuantity = $request->quantity;
                    }
                    // 在庫削減処理の場合(-1)
                    if($request->type === \Constant::PRODUCT_LIST['reduce'])
                    {
                        $newQuantity = $request->quantity * -1;
                    }

                    /* 在庫情報更新処理 */
                    Stock::create([
                        // id=product情報より取得
                        'product_id' => $product->id,
                        // type:$request->typeより取得
                        'type' => $request->type,
                        // 数量：newQuantityより取得
                        'quantity' => $newQuantity
                    ]);

                }, 2);

            }catch(Throwable $e){
                Log::error($e);
                throw $e;
            }

            // redirect to owner/products/index.blade.php+ flassmessage
            return redirect()
            ->route('owner.products.index')
            ->with('info','商品情報を更新しました。');

        }
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
