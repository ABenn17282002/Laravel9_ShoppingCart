<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // table名の定義
    protected $table = 't_stocks';

    // t_stocks_tableの定義
    protected $fillable = [
        'product_id',
        'type',
        'quantity'
    ];

}
