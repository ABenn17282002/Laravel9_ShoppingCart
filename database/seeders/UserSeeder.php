<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// DB,Hashクラスのインポート
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'赤木 梨沙',
            'email'=>'Risa_Akagi@nxtjdkld.meycr.it',
            'password'=>Hash::make('kf4ieuMNJzlGKKpL'),
            'created_at'=> '2021/09/10 11:21:30'
        ]);
    }
}
