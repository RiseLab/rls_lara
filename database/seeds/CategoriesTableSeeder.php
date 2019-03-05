<?php

use Illuminate\Database\Seeder;
use App\Category;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('ru_RU');

        for ($i = 0; $i < 10; $i++) {
        	$title = $faker->country;
        	Category::create([
        		'title' => $title,
		        'alias' => Str::slug($title)
	        ]);
        }
    }
}
