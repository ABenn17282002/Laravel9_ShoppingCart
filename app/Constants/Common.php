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

    /* 表示順の定数化 */
    // おすすめ順
    const ORDER_RECOMMEND = '0';
    // 高い順
    const ORDER_HIGHER = '1';
    // 安い順
    const ORDER_LOWER = '2';
    // 新しい順
    const ORDER_LATER = '3';
    // 古い順
    const ORDER_OLDER = '4';

    // 定数のList化
    const SORT_ORDER = [
        'recommend' => self::ORDER_RECOMMEND,
        'higherPrice' => self::ORDER_HIGHER,
        'lowerPrice' => self::ORDER_LOWER,
        'later' => self::ORDER_LATER,
        'older' => self::ORDER_OLDER
    ];
}

