<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// DB,Hashクラスのインポート
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name'=>'山本 篤志',
            'email'=>'yamamoto@info.com',
            'password'=>Hash::make('$U!8C@vnFWfWmmR'),
            'created_at'=> '2021/11/10 11:20:30'
        ]);
    }
}
