<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{

    // ServiceProviderTest関数(パスワードの暗号/復号化)
    public function showServiceProviderTest()
    {
        // 暗号化用サービスプロバイダーの生成
        $encrypt = app()->make('encrypter');
        // PWの暗号化
        $password = $encrypt->encrypt('0457218138atyaba');

        // 作成したサンプルサービスプロバイダーの生成
        $sample = app()->make('ServiceProviderTest');

        // サンプルプロバイダー,暗号化したPWの表示,PWの復号化
        dd($sample, $password, $encrypt->decrypt($password));
    }

    // Servicecontainerの利用を定義する関数
    public function showServiceContainerTest()
    {
        // ServiceContainerを追加
        app()->bind('LifeCycleTest',function(){
            return 'ライフサイクルテスト';
        });

        // ライフサイクルの生成->変数に格納
        $test =app()->make('LifeCycleTest');

        /*---------インスタンス実行型--------*/

        $message1 = new Message1();
        $sample1 = new Sample1($message1);
        $sample1->run();

        /*----------------------------------*/

        /*---------サービスコンテナ型---------*/

        // Sample2クラスから結合
        app()->bind('sample',Sample2::class);
        // ライフサイクル生成
        $sample2 = app()->make('sample');
        $sample2->run();

        /*----------------------------------*/

        // 生成したライフサイクルとServiceContainerを表示
        dd($test,app());
    }
}

/* Messageを取得するクラス(インスタンス実行型) */
class Sample1
{
    public $message1;
    // 初期化(Message1 $meesages1)
    public function __construct(Message1 $message1)
    {
        // classのmessage1プロパティに$message1を格納
        $this->message1 =$message1;
    }

    // オブジェクトより$message1を取得し,messageを送付
    public function run()
    {
        $this->message1->send();
    }
}

/* Messageを取得するクラス(サービスコンテナ型) */
class Sample2
{
    public $message2;
    public function __construct(Message2 $message2)
    {
        $this->message2 =$message2;
    }

    public function run()
    {
        $this->message2->send();
    }
}

/* Message作成用クラス(インスタンス実行型) */
class Message1
{
    public function send()
    {
        echo('インスタンス実行後の表示<br/>');
    }
}

/* Message作成用クラス(サービスコンテナ型) */
class Message2
{
    public function send()
    {
        echo('サービスコンテナを使ったパターン');
    }
}
