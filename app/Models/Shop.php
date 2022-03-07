<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Ownerモデルの使用
use App\Models\Owner;

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

}
