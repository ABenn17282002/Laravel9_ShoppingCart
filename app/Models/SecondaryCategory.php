<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryCategory extends Model
{
    use HasFactory;

    /**
    * secondary_tableに関係するprimary_table情報を全てを取得
    */
    public function primary()
    {
        return $this->belongsTo(PrimaryCategory::class);
    }
}
