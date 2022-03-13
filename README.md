## Laravel9.0_Docker
## インストール方法

## インストール後の実施事項
### 1. Debugの導入
.envファイルのAPP_DEBUG<br/>
検証環境の場合:true<br/>
本番環境の場合:false

### 2. EagerLoader時のページネーション
→検証中になります。暫定対応get()で画像一覧取得のみ。

### 3.DBの構築作業
下記コマンドで出来ます。<br/>
```
> cd projectfolder
> ./vendor/bin/sail up -d
> source ~/.bash_profile
> sail shell
// DB再構築用コマンド(既存DBがなければmigrateまででOK)
sail@******:/var/www/html$ php artisan migrate:fresh --seed
// 以下messageが出ればOK！
Dropped all tables successfully.
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
　　　：
Migrated:  2022_03_13_231745_create_stocks_table (113.74ms)
Seeding: Database\Seeders\AdminSeeder
　　　：
Seeded:  Database\Seeders\StockSeeder (5.39ms)
```
### 4. php artsan tinkerでモデルの確認
```
sail@******:/var/www/html$php artisan tinker
Psy Shell v0.11.1 (PHP 8.1.2 — cli) by Justin Hileman
>>> $product = new App\Models\Product
=> App\Models\Product {#4549}
>>> $product::find(1)->stock
=> Illuminate\Database\Eloquent\Collection {#4564
     all: [
       App\Models\Stock {#4565
         id: 1,
         product_id: 1,
         type: 1,
         quantity: 5,
         created_at: null,
         updated_at: null,
       },
       App\Models\Stock {#4568
         id: 2,
         product_id: 1,
         type: 1,
         quantity: -2,
         created_at: null,
         updated_at: null,
       },
     ],
   }
// 合計を計算
>>> $product::find(1)->stock->sum('quantity')
=> 3
```
※ EagerLoaderでのページネーションは検証中です。
