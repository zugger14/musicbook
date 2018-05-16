<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('admins')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'gender' => 1,
            'avatar' => 'aa',
            'slug' => 'aa',
            'password' => bcrypt('secret'),
            'remember_token' => 'asasdss'
        ]);
    }
}
