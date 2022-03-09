<?php

namespace App\Services;

/* Service切り離しのため
 ShopControllerクラスから移動 */
// storage用モデル
use Illuminate\Support\Facades\Storage;
//  画像リサイズ用モデル
use InterventionImage;

// ImageServiceクラス
class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        // dd($imageFile);

        // 画像ファイルが配列形式か確認
        if(is_array($imageFile))
        {
          $file = $imageFile['image'];
        } else {
          $file = $imageFile;
        }

        // 乱数値でファイル名作成
        $fileName = uniqid(rand().'_');
        // image_fileを拡張
        $extension = $file->extension();
        // 拡張したfile名+乱数値で再度ファイル名を生成
        $fileNameToStore = $fileName. '.' . $extension;
        // 1920 * 1080sizeに画像を変更
        $resizedImage = InterventionImage::make($file)
        ->resize(1920, 1080)->encode();

        // publicフォルダ配下にshopsフォルダを作り、画像を保存
        Storage::put('public/' . $folderName . '/' . $fileNameToStore,
        $resizedImage );

        // fileNameToStore使用のため、$fileNameToStoreを返す
        return $fileNameToStore;
    }
}
