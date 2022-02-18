<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// DB,Hashクラスのインポート
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->insert([
            [
                'name'=>'test1',
                'email'=>'test1@test.com',
                'password'=>Hash::make('test98721'),
                'created_at'=> '2021/11/10 11:20:31'
            ],
            [
                'name'=>'test2',
                'email'=>'test2@test.com',
                'password'=>Hash::make('test987212'),
                'created_at'=> '2021/11/10 11:20:32'
            ],
            [
                'name'=>'test3',
                'email'=>'test3@test.com',
                'password'=>Hash::make('test987213'),
                'created_at'=> '2021/11/10 11:20:33'
            ],
        ]);
    }
}
