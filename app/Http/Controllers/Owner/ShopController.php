<?php

namespace App\Http\Controllers\Owner;

use App\Models\Shop;
use Illuminate\Http\Request;
// Shopモデル
use App\Http\Controllers\Controller;
// Auth認証用モデル
use Illuminate\Support\Facades\Auth;
// storage用モデル
use Illuminate\Support\Facades\Storage;
//  画像リサイズ用モデル
use InterventionImage;


class ShopController extends Controller
{
    /*ログイン済みOwnerのみ表示させるため
    コンストラクタの設定 */
    public function __construct()
    {
        $this->middleware('auth:owners');

        // コントローラミドルウェア
        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter('shop')); // 文字列
            // dd(Auth::id()); // 数字

            // shop_idの取得
            $id = $request->route()->parameter('shop');
            // null判定
            if(!is_null($id)){
                // OwnerIdの取得
                $shopsOwnerId= shop::findOrFail($id)->owner->id;
                // 文字列→数値に変換
                $shopId = (int)$shopsOwnerId;
                // 認証済のidを取得
                $ownerId = Auth::id();
                // shopIDとownerIDが不一致の場合
                if($shopId !== $ownerId){
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
        // \phpinfo();

        // Login済Owner_idの取得
        $shops = Shop::where('owner_id', Auth::id())->get();

        // owner/index.balde.phpにshops変数付で返す
        return view('owner.shops.index',
        compact('shops'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idがあればそのページ,なければ404
        $shop = Shop::findOrFail($id);

        // owner/shops/edit.blade.phpにshop変数付でページを返す
        return \view('owner.shops.edit',
        \compact('shop'));
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
        // 一時フォルダ上で画像を保存
        $imageFile = $request->image;
        // 画像がnullでなく、upload出来ている場合
        if(!is_null($imageFile) && $imageFile->isValid()){

            // Storage::putFile('public\shops',$imageFile);

            // 乱数値でファイル名作成
            $fileName = uniqid(rand().'_');
            // image_fileを拡張
            $extension = $imageFile->extension();
            // 拡張したfile名+乱数値で再度ファイル名を生成
            $fileNameToStore = $fileName. '.' . $extension;
            // 1920 * 1080sizeに画像を変更
            $resizedImage = InterventionImage::make($imageFile)
            ->resize(1920, 1080)->encode();
            // dd($imageFile,$resizedImage);

            // publicフォルダ配下にshopsフォルダを作り、画像を保存
            Storage::put('public/shops/' . $fileNameToStore,
            $resizedImage );
        }

        // 画像保存後shops.indexにリダイレクト
        return redirect()->route('owner.shops.index');
    }
}
