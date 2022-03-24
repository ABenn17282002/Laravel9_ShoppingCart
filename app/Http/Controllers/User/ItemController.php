<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Productクラスの使用
use App\Models\Product;

class ItemController extends Controller
{
    // indexページの表示
    public function index()
    {
        // ProductTableの内容を全取得
        $products = Product::all();
        return view('user.index',\compact('products'));
    }
}
