<?php

namespace App\Models;

// SecondaryCategoryモデルの使用
use App\Models\SecondaryCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrimaryCategory extends Model
{
    use HasFactory;

    public function secondary()
    {
        // SecondaryCategory_tableと1対多の関係
        return $this->hasMany(SecondaryCategory::class);
    }
}
