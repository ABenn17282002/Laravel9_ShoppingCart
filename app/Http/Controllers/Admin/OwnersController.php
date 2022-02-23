<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;  // Eloquent エロクアント
use Illuminate\Support\Facades\DB; // QueryBuilder クエリービルダー
use Carbon\Carbon;   // 日付を扱うクラス
use Illuminate\Support\Facades\Hash;  // 暗号化クラス
use Illuminate\Validation\Rules;      // validationクラス

class OwnersController extends Controller
{

    /*ログイン済みユーザーのみ表示させるため
    コンストラクタの設定 */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Owne_tableの名前,email,作成日を取得 <-id情報がないと編集できないので追加
        $owners = Owner::select('id','name','email','created_at')->get();

        //  admin/owners/index.blade.phpに$owners変数を渡す。
        return \view('admin.owners.index',compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // admin/owners/create.blade.phpに返す
        return \view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // name,email,passowrdの保存
        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // owners.indexページへリダイレクト flashmessage
        return \redirect()->route('admin.owners.index')
        ->with('success','オーナー登録が完了しました。');
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
        // idがなければ404画面
        $owner = Owner::findOrFail($id);
        // dd($owner);

        // admin/owners/edit.blade.phpにowner変数(owner_id)を渡す。
        return \view('admin.owners.edit',\compact('owner'));
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
        // idがなければ404画面
        $owner = Owner::findOrFail($id);
        // フォームから取得した値を代入
        $owner -> name = $request->name;
        $owner -> email = $request->email;
        // passwordは暗号化
        $owner -> password = Hash::make($request->password);
        // 情報を保存
        $owner ->save();

        return \redirect()
        ->route('admin.owners.index')
        ->with('update','オーナー情報を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //ソフトデリート
        Owner::findOrFail($id)->delete();

        return \redirect()
        ->route('admin.owners.index')
        ->with('trash','オーナー情報をゴミ箱へ移しました');
    }

    /* 期限切れOwner情報の取得 */
    public function expiredOwnerIndex()
    {
        // softDeleteのみを取得
        $expiredOwners = Owner::onlyTrashed()->get();
        return view('admin.expired-owners',\compact('expiredOwners'));
    }

    /* 期限切れOwner情報の完全削除 */
    public function expiredOwnerDestroy($id)
    {
        // 論理削除したuserを物理削除する
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.expired-owners.index')
        ->with('delete','オーナー情報を完全に削除しました');;
    }
}
