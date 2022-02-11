<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Componentを表示するクラス
class ComponentTestController extends Controller
{
    // component-test1表示
    public function showComponent1(){

        return \view('tests.component-test1');
    }

    // component-test2表示
    public function showComponent2(){

        return \view('tests.component-test2');
    }
}
