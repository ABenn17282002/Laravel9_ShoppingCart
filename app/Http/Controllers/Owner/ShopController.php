<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Shopモデル
use App\Models\Shop;
// Auth認証用モデル
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /*ログイン済みOwnerのみ表示させるため
    コンストラクタの設定 */
    public function __construct()
    {
        $this->middleware('auth:owners');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Login済Owner_idの取得
        $ownerId = Auth::id();
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

    }
}
