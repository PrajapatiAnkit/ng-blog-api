<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
         \App\Models\Post::factory(10)->create();
    }
}
