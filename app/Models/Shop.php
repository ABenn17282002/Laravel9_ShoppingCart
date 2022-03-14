<?php

namespace App\Models;
// Ownerモデルの使用
use App\Models\Owner;
// Productモデルの使用
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    /**
    * shop_tableの定義
    */
    protected $fillable =[
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling'
    ];

    /**
     * このshopにいるオーナー情報を全てを取得
    */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    /**
    * shopに関連しているproducts情報を取得
    * 1 対 多モデル
    */
    public function product()
    {
        return $this->hasMany(Product::class);
    }

}
