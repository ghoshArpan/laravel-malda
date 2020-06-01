<?php

use Illuminate\Database\Seeder;
use App\tbl_user;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tbl_user::create([
            'mobile_no' => '6767667776',
            'name' => 'Virat Kohli',
            'designation' => "DM",
            'user_type' => 0,
        ]);
    }
}
