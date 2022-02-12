<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    // Servicecontainerの利用を定義する関数
    public function showServiceContainerTest()
    {
        // ServiceContainerを追加
        app()->bind('LifeCycleTest',function(){
            return 'ライフサイクルテスト';
        });

        // ライフサイクルの生成->変数に格納
        $test =app()->make('LifeCycleTest');

        // 生成したライフサイクルとServiceContainerを表示
        dd($test,app());
    }
}
