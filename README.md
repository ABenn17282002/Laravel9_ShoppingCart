## Laravel9.0_Docker
## インストール方法

## インストール後の実施事項
### 1. エイリアスの設定
(1) .bash_profileファイルに下記記載。
```
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```
(2) home/user直下に置く

(3) 下記コマンド実施
```
cd projectfolder

// Dockeの起動
/laravel_project/ShoppingCart$ ./vendor/bin/sail up -d
// Server環境にLogin
/laravel_project/ShoppingCart$  source ~/.bash_profile
~/laravel_project/ShoppingCart$ sail shell
sail@*******:/var/www/html$　
```
<参考>[3秒で終わるLaravel開発環境構築](https://qiita.com/print_r_keeeng/items/544d14e4e0eab0508985)

### 2. 画像の設定
画像のダミーデータは public/imagesフォルダ内に 
sample1.jpg 〜 sample6.jpg として保存しています。

(1) 1.の手順実施後、以下コマンドにてstorageフォルダーにリンク
```
sail@*******:/var/www/html$php artisan storage:link
// 下記messageが出ればOK！
The [/var/www/html/public/storage] link has been connected to [/var/www/html/storage/app/public].
The links have been created.
```

(2) sample1~6.jpgをpublic/imagesからstorage/app/public/productsフォルダ内に 
保存する
(productsフォルダがない場合は作成してください。)

(3) php aritsan migrate:refresh --seedでtable再作成後、ownerLoginUserで
画像表示タグ選択で画像が表示されます。

```
sail@*******:/var/www/html$php artisan migrate:fresh --seed
// 下記messageが出ればOK！
Dropped all tables successfully.
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (64.59ms)
   :
Migrated:  2022_03_07_224854_create_images_table (121.56ms)
Seeding: Database\Seeders\AdminSeeder
 　:
Seeded:  Database\Seeders\ImageSeeder (5.87ms)
Database seeding completed successfully.
```
