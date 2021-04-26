<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            ['id' => 1, 'name' => 'Angular', 'slug' => 'angular'],
            ['id' => 2, 'name' => 'React', 'slug' => 'react'],
            ['id' => 3, 'name' => 'Vue Js','slug' => 'vue-js'],
        ]);
    }
}
