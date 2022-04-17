## Laravel9.0_Docker
このコードは以下Udemyセミナーを参考にDocker上に作成したものです。<br>
■ [【Laravel】マルチログイン機能を構築し本格的なECサイトをつくってみよう【Breeze/tailwindcss】](https://www.udemy.com/course/laravel-multi-ec/)<br>

## 事前準備
WindowsとMacで環境が異なります。それぞれ以下を参照にしてください。<br>

(1) docker等のインストール<br>
・Mac<br>
■ MacにDocker Desktopインストール<br>
https://docs.docker.jp/docker-for-mac/install.html#install-and-run-docker-desktop-on-mac

・ Windows<br>
■ Windows10におけるLaravel Sailの最適な開発環境の作り方（WSL2 on Docker）
https://zenn.dev/goro/articles/018e05bee92aa1

(2) エイリアスの設定<br>
[エイリアスの設定](https://qiita.com/print_r_keeeng/items/544d14e4e0eab0508985#%E3%82%A8%E3%82%A4%E3%83%AA%E3%82%A2%E3%82%B9%E8%A8%AD%E5%AE%9A)
## インストール方法
(1) 「main」とある部分から必要なbrachを選択<br>

(2) 「Code」→「DownloadZip」でソースコードをダウンロード<br>

(3)　下記コマンドでsail dockerのダウンロード<br>
```
> curl -s https://laravel.build/<アプリ名>| bash

latest: Pulling from laravelsail/php81-composer
eff15d958d66: Pull complete 
　：
Application ready! Build something amazing.
Sail scaffolding installed successfully.
```
(4) 事前にbranchからdownloadしたファイルを解凍し、その中身をコピー<br>
sail dockerでインストールしたフォルダーにペースト

(5) .envの設定
.env.exampleを.envに変更し以下の部分を変更してください。<br>
```
# DB設定
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 → mysqlに変更
DB_PORT=3306
DB_DATABASE=shoppingcart
DB_USERNAME=root  → User名に変更
DB_PASSWORD=　　　 → 任意PW設定

## StripeKeyの設定(インストール後の実施事項参照)
STRIPE_PUBLIC_KEY=
STRIPE_SECRET_KEY=
```

(6) 以下コマンドでdockerの起動<br>
※ 起動にはInternet環境により異なりますが、20分程かかります。<br>
```
// プロジェクトフォルダに移動
cd projectfolder

// dockerの起動
./vendor/bin/sail up -d
```

(7) 以下コマンドでComposerのキャッシュを削除し、<br>
再インストール
```
// Server環境にlogin
> source ~/.bash_profile
> sail shell
// Composer Cashの削除
sail@*******:/var/www/html$ composer clearcache
// Composerの再インストール
sail@*******:/var/www/html$ composer install
```

(8) `./vendor/bin/sail down`でdockerを停止し、<br>
`./vendor/bin/sail up -d`でdocker再起動

(9) localhost:8573でブラウザ確認<br>
login画面が確認出来ればOK！

(10) `php artisan migrate:fresh --seed`で<br>
MySQL上にtableとデータを作成。

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

### 4.メールの非同期処理について
メールの通知処理は非同期処理にて実行しております。<br>
必ずdocker起動後は、以下のコマンドでQUE:Workerを起動させて下さい。<br>

■ Que:Workerの起動
```
~/laravel_project/ShoppingCart$ source ~/.bash_profile
:~/laravel_project/ShoppingCart$ sail shell
// Que:Workerコマンド
sail@********:/var/www/html$ php artisan queue:work
[2022-04-12 23:10:07][21] Processing: App\Jobs\SendThanksMail
[2022-04-12 23:10:07][21] Processed:  App\Jobs\SendThanksMail
```

■ Que:Workerの停止<br>
※ 停止した場合でもQue:JobはDataBaseに保存されています。
```
sail@********:/var/www/html$ php artisan queue:restart
Broadcasting queue restart signal.
```
