<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// shopモデルの追加
use App\Models\Shop;
use App\Models\SecondaryCategory;

class Product extends Model
{
    use HasFactory;

    /**
    * Prouduct(製品)に関わるshop情報を全て取得
    */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
    * Prouduct(製品)に関わるSecondaryCategory情報を全て取得
    * メソッド名をモデル名から変える場合は第２引数必要
    */
    public function category()
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    /**
    * Prouduct(製品)に関わる画像情報を全て取得
    * メソッド名をモデル名から変える場合は第２引数必要
    *  - (カラム名と同じメソッドは指定できないので名称変更)
    *  - 第２引数で_id がつかない場合は 第３引数で指定必要
    */
    public function imageFirst()
    {
       return $this->belongsTo(Image::class, 'image1', 'id');
    }
}
