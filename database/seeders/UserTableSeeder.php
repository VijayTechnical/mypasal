<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user4321'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'utype' => 'USR'
        ]);
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin4321'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'utype' => 'ADM'
        ]);
    }
}
