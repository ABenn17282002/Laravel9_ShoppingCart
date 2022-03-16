<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// 認証モデルの使用
use Illuminate\Support\Facades\Auth;
// DBFacadeの使用
use Illuminate\Support\Facades\DB;
// Image,Product,Owner,productsモデルの使用
Use App\Models\Image;
use App\Models\Product;
use App\Models\Owner;
// PrimaryCategoryに修正
use App\Models\PrimaryCategory;
// shopモデルの使用を追加
use App\Models\Shop;
// Stockモデルの使用
use App\Models\Stock;

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
    public function store(Request $request)
    {
        // dd($request);
        // validation
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'information' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
            'shop_id' => ['required', 'exists:shops,id'],
            'category' => ['required', 'exists:secondary_categories,id'],
            'image1' => ['nullable', 'exists:images,id'],
            'image2' => ['nullable', 'exists:images,id'],
            'image3' => ['nullable', 'exists:images,id'],
            'image4' => ['nullable', 'exists:images,id'],
            'is_selling' => ['required']
        ]);


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
