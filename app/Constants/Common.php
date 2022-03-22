<?php

// Constantsの名前空間の使用
namespace App\Constants;

class Common
{
    // 増減の定数の作成
    const PRODUCT_ADD ='1';
    const PRODUCT_REDUCE = '2';

    // 定数のList化
    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,
        'reduce' => self::PRODUCT_REDUCE
      ];
}

