<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
            [
                'name' => 'First User',
                'email' => 'first_user@loansolutions.ph',
                'password' => bcrypt('anonymous')
            ],
            [
                'name' => 'Second User',
                'email' => 'second_user@loansolutions.ph',
                'password' => bcrypt('anonymous')
            ]
        ));
    }
}
