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
        // Owne_tableの名前,email,作成日を取得
        $owners = Owner::select('name','email','created_at')->get();

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