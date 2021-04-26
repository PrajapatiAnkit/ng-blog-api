<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'name' => 'Ankit',
            'email' => 'ankit@gmail.com',
            'contact' => 9999999999,
            'password' => bcrypt(123456)
        ]);
    }
}
