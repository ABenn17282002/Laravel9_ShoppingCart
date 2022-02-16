<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * データベースに対するデータ設定の実行
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            OwnerSeeder::class,
        ]);
    }
}
