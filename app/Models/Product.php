<?php

namespace App\Models;

// shopモデルの使用
use App\Models\Shop;
// stockモデルの使用
use App\Models\Stock;
//  Userモデルの使用
use App\Models\User;
use App\Models\SecondaryCategory;
// DBクラスのインポート
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    // Products_tableの定義
    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

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

    public function imageSecond()
    {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }

    public function imageThird()
    {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }

    public function imageFourth()
    {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    /**
    * Prouduct(製品)に関わるStock情報を全て取得
    * 1対多モデル
    */
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    /**
    * Prouduct(製品)に関わるcarts情報を全て取得
    * 1対多モデル
    */
    public function users()
    {
        return $this->belongsToMany(User::class,'carts')
        ->withPivot(['id', 'quantity']);
    }

    /**
    * 商品在庫が1以上のものを表示するクエリスコープを設定
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeAvailableItems($query)
    {
        /* 商品在庫数が1以上のもの */
        $stocks = DB::table('t_stocks')->select('product_id',
        // select内でsumを使うためクエリビルダのDB::rawで対応
        DB::raw('sum(quantity) as quantity'))
        ->groupBy('product_id')
        ->having('quantity', '>', 1);

        /* 上記queryに合致する商品詳細を表示 */
        return $query
        // $stockのクエリーをSubqueryとして使用
        ->joinSub($stocks, 'stock', function($join){
            // Join product on products.id = stock.product_id
            $join->on('products.id', '=', 'stock.product_id');
            })
            // Join shops on products.shop_id = shops.id
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            // Eloquenからクエリービルダーに変更したため、tableの紐づくidを同士を結ぶ
            ->join('secondary_categories', 'products.secondary_category_id', '=',
            'secondary_categories.id')
            ->join('images as image1', 'products.image1', '=', 'image1.id')
            ->join('images as image2', 'products.image2', '=', 'image2.id')
            ->join('images as image3', 'products.image3', '=', 'image3.id')
            ->join('images as image4', 'products.image4', '=', 'image4.id')
            // where shops.is_selling =1
            ->where('shops.is_selling', true)
            // where products.is_selling =1
            ->where('products.is_selling', true)
            // select products.id,name,price,sort_order,information,
            // secondary_categories.name,image1.filename
            ->select('products.id as id', 'products.name as name', 'products.price'
            ,'products.sort_order as sort_order'
            ,'products.information', 'secondary_categories.name as category'
            ,'image1.filename as filename');
    }

    /**
    * 表示順クエリスコープを設定
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query $sortOrder
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeSortOrder($query, $sortOrder)
    {
        // sort_orderがnull and sortorder = 0の場合:並び順を昇順表示
        if($sortOrder === null || $sortOrder === \Constant::SORT_ORDER['recommend']){
            return $query->orderBy('sort_order', 'asc') ;
        }

        // sortorder = 1の場合:価格を降順表示
        if($sortOrder === \Constant::SORT_ORDER['higherPrice']){
            return $query->orderBy('price', 'desc') ;
        }

        // sortorder = 2の場合:価格を昇順表示
        if($sortOrder === \Constant::SORT_ORDER['lowerPrice']){
            return $query->orderBy('price', 'asc') ;
        }

        // sortorder = 3の場合:作成日を降順表示
        if($sortOrder === \Constant::SORT_ORDER['later']){
            return $query->orderBy('products.created_at', 'desc') ;
        }

        // sortorder = 4の場合:作成日を昇順表示
        if($sortOrder === \Constant::SORT_ORDER['older']){
            return $query->orderBy('products.created_at', 'asc') ;
        }
    }


    /**
    * カテゴリー検索クエリーを設定
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query $categoryId
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeSelectCategory($query, $categoryId)
    {
        if($categoryId !== '0'){
            // 0以外が選択した場合:カテゴリーを選択
            return $query->where('secondary_category_id', $categoryId);
        } else {
            return;
        }
    }

    /**
    * キーワードを検索するクエリーを設定
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query $keyword
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeSearchKeyword($query, $keyword)
    {
        // 検索ワードがある場合
        if(!is_null($keyword)){
            // 全角スペースを半角に変換
            $spaceConvert =mb_convert_kana($keyword,'s');
            // 空白で区切る
            $keywords =\preg_split('/[\s]+/', $spaceConvert,-1,PREG_SPLIT_NO_EMPTY);
            // 単語をループで回す
            foreach($keywords as $word)
            {
                $query->where('products.name','like','%'.$word.'%');
            }
            // dd($query);
            return $query;

        } else {
            return;
        }
    }
}
