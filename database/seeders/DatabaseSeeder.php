<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * データベースに対するデータ設定の実行関数
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            OwnerSeeder::class,
            // shopクラスの追加
            ShopSeeder::class,
            // Imageクラスの追加
            ImageSeeder::class,
            // Categoryクラスの追加
            CategorySeeder::class,
            // Productクラスの追加
            ProductSeeder::class,
            // Stockクラスの追加
            StockSeeder::class,
        ]);
    }
}
