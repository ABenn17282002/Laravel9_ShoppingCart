<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 認証用モデルのインポート
use Illuminate\Foundation\Auth\User as Authenticatable;
// softDelete用クラス
use Illuminate\Database\Eloquent\SoftDeletes;
// shopモデルの追加
use App\Models\Shop;

// 認証可能なUserクラスを拡張したOwnerクラス
class Owner extends Authenticatable
{
    //
    use HasFactory, SoftDeletes;

    /** model内容はUserモデルと同様
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
    * Ownerに関連しているshop情報を取得
    * 1 対 1モデル
    */
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    /**
    * Ownerに関連しているimage情報を取得
    * 1 対 多モデル
    */
    public function image()
    {
        return $this->hasMany(Image::class);
    }
}
