## Laravel9.0_Docker
## インストール方法

## インストール後の実施事項
### 1.商品画像と店舗画像について
商品画像と店舗画像はpublic/imagesフォルダ内に下記のように<br>
それぞれ保存しております。

<商品画像>sample1.jpg 〜 sample6.jpg<br>
<店舗画像>shop1.jpg ～ shop2.jpg<br>

商品画像と店舗画像を表示させたい場合は下記の手順を実施してください。<br>

(1) sample1~6.jpgをpublic/imagesからstorage/app/public/productsフォルダ内に 
保存する(productsフォルダがない場合は作成してください。)<br>

また, shop1.jpg ～ shop2.jpgをstorage/app/public/shopsフォルダ内に保存してください。<br>
(shopsフォルダがない場合は作成してください。)<br>

(2) 下記コマンドでDockerを起動し、Server環境へlogin
```
cd projectfolder

// Dockeの起動
/laravel_project/ShoppingCart$ ./vendor/bin/sail up -d
// Server環境にLogin
/laravel_project/ShoppingCart$  source ~/.bash_profile
~/laravel_project/ShoppingCart$ sail shell
sail@*******:/var/www/html$　
```

(3) 以下コマンドにてstorageフォルダーにリンク
```
sail@*******:/var/www/html$php artisan storage:link
// 下記messageが出ればOK！
The [/var/www/html/public/storage] link has been connected to [/var/www/html/storage/app/public].
The links have been created.
```

(4) php aritsan migrate:refresh --seedでtable再作成後、User権限でLogin後
商品詳細画面で画像のスライダーと店舗画像が表示されます。

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

### 2. StripeAPIの使用方法について
StripeAPIを使用するには、[Stripe](https://stripe.com/jp)にアクセスし会員登録を行い、<br>
左上の「アカウント」作成よりアカウント情報を作成する必要性があります。<br>
詳細は以下を参照にしてください。<br>

■ Stripeアカウントの作り方<br>
[決済サービス「Stripe」のアカウント作成〜サブスクリプションの設定まとめ](https://macareux.co.jp/blog/stripe-subsctiption)


### 3. テストメールの設定方法
XAMPP、MAMPPとDocker上では、テストメール送信方法が異なります。<br>
詳細は以下を参照にしてください。<br>

■ XAMPP,MAMPPでのテストメール設定方法<br>
[MailTrapのダミーのSMTPサーバ使ってテストメール送信](https://reffect.co.jp/laravel/mailtrap-dummy-smtp-server)<br>

■ Dockerでのテストメール設定方法<br>
[Docker+LaravelでMailhogを使う](https://qiita.com/munimuni/items/b902f2c3ec643ed78e4a)<br>
