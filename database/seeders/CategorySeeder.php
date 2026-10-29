<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'movies', 'name' => 'Movies', 'icon' => 'bi-film', 'sort_order' => 10],
            ['slug' => 'tv-shows', 'name' => 'TV Shows', 'icon' => 'bi-tv', 'sort_order' => 20],
            ['slug' => 'games', 'name' => 'Games', 'icon' => 'bi-controller', 'sort_order' => 30],
            ['slug' => 'curiosities', 'name' => 'Curiosities', 'icon' => 'bi-lightbulb', 'sort_order' => 40],
            ['slug' => 'real-stories', 'name' => 'Real Stories', 'icon' => 'bi-book', 'sort_order' => 50],
            ['slug' => 'reviews', 'name' => 'Reviews', 'icon' => 'bi-file-earmark-text', 'sort_order' => 60],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
