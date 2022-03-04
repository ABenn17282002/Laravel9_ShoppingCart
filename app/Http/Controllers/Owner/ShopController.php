<?php

namespace App\Http\Controllers\Owner;

use App\Models\Shop;
use Illuminate\Http\Request;
// Shopモデル
use App\Http\Controllers\Controller;
// Auth認証用モデル
use Illuminate\Support\Facades\Auth;
// UploadImageRequestクラス
use App\Http\Requests\UploadImageRequest;
// ImageServiceクラス
use App\Services\ImageService;


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
     * @param  \Illuminate\Http\UploadImageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UploadImageRequest $request, $id)
    {
        // 一時フォルダ上で画像を保存
        $imageFile = $request->image;
        // 画像がnullでなく、upload出来ている場合
        if(!is_null($imageFile) && $imageFile->isValid()){
            // ImageServiceクラスのuploadmethodに$imageFileとフォルダ名を渡す。
            $fileNameToStore = ImageService::upload($imageFile,'shops');
        }

        // 画像保存後shops.indexにリダイレクト
        return redirect()->route('owner.shops.index');
    }
}
