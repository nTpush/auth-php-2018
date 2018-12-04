<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            [
                'name'=>'shining',
                'email'=>'admin@qq.com',
//          'password'=>\Illuminate\Support\Facades\Crypt::encode('shining')
                'password'=>bcrypt('shining')
            ],
        ]);
    }
}
