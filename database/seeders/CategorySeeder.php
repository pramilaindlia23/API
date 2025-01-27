<?php

namespace Database\Seeders;
use App\Models\Category;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['category_name' => 'category A']);
        Category::create(['category_name' => 'Category B']);
        Category::create(['category_name' => 'Category C']);
        Category::create(['category_name' => 'Category D']);
        Category::create(['category_name' => 'Category E']);
        Category::create(['category_name' => 'Category F']);
        Category::create(['category_name' => 'Category G']);
        Category::create(['category_name' => 'Category H']);
        Category::create(['category_name' => 'Category I']);
        Category::create(['category_name' => 'Category J']);

    }
}
