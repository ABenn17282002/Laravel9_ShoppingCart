<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Imageと認証モデル
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
// Productクラスの使用
use App\Models\Product;
// UploadImageRequestクラス
use App\Http\Requests\UploadImageRequest;
// ImageServiceの使用
use App\Services\ImageService;
// Storage用モジュールの使用
use Illuminate\Support\Facades\Storage;
class ImageController extends Controller
{

    /*ログイン済みImageのみ表示させるため
    コンストラクタの設定 */
    public function __construct()
    {
        $this->middleware('auth:owners');

        // コントローラミドルウェア
        $this->middleware(function ($request, $next) {
            // image_idの取得
            $id = $request->route()->parameter('image');
            // null判定
            if(!is_null($id)){
                // images_OwnerIdの取得
                $imagesOwnerId= Image::findOrFail($id)->owner->id;
                // 文字列→数値に変換
                $imageId = (int)$imagesOwnerId;
                // imageIdが認証済でない場合
                if($imageId  !== Auth::id()){
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
        // 認証済Owner_idに紐づくImageIDを取得
        $images = Image::where('owner_id', Auth::id())
        // 降順取得20件まで
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        // owner/images/index.balde.phpにimages変数付で返す
        return view('owner.images.index',
        compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // owner/images/create.blade.phpにviewを返す
        return \view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UploadImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        // 複数ファイルを取得
        $imageFiles = $request->file('files');
        // 配列が空でない場合
        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                // 製品フォルダ内に画像を1つずつupload
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                // image_tableのowneridとfilenameに記録
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
        }

        // redirect owner/images/index.blade.php + flashmessage
        return redirect()
        ->route('owner.images.index')
        ->with('info','画像登録を実施しました。');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Image_idの取得(ない場合:404)
        $image = Image::findOrFail($id);
        return \view('owner.images.edit',\compact('image'));
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
        // validation
        $request->validate([
            'title' => ['string', 'max:50'],
        ]);

        // image_idの取得
        $image = Image::findOrFail($id);
        // 取得したImageIdからtitleを取得
        $image ->title = $request->title;
        // 情報を保存
        $image ->save();

        // redirect owner/images/index.blade.php + flashmessage
        return redirect()
        ->route('owner.images.index')
        ->with('info','画像情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // imageIDを取得
        $image = Image::findOrFail($id);

        // 削除したい画像をProductで使っているかの確認
        $imageInProducts =Product::where('image1', $image->id)
        ->orWhere('image2', $image->id)
        ->orWhere('image3', $image->id)
        ->orWhere('image4', $image->id)
        ->get();

        // 画像をProduct側で使用の場合、image1-4を確認してnullにする。
        if($imageInProducts){
            $imageInProducts->each(function($product) use($image){
                if($product->image1 === $image->id){
                    $product->image1 = null;
                    $product->save();
                }
                if($product->image2 === $image->id){
                    $product->image2 = null;
                    $product->save();
                }
                if($product->image3 === $image->id){
                    $product->image3 = null;
                    $product->save();
                }
                if($product->image4 === $image->id){
                    $product->image4 = null;
                    $product->save();
                }
            });
        }

        // file情報取得
        $filePath = 'public/products'. $image->filename;

        // fileがあれば画像削除
        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        // DB情報削除(←Productで使用中の画像を削除するとエラーが出る！)
        Image::findOrFail($id)->delete();

        // redirect owner/images/index.blade.php + flashmessage
        return redirect()
        ->route('owner.images.index')
        ->with('delete','画像を完全に削除しました');;
    }
}
