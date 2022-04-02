<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
    * cart_tableの定義
    *
    */
    protected $fillable =[
        'user_id',
        'product_id',
        'quantity'
    ];
}
