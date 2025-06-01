<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $categories = ['Painting', 'Drawings', 'Mixed Media', 'Sculpture', 'Collage', 'Digital', 'Printmaking', 'Installation'];
        $categories_ar = ['لوحة', 'رسومات', 'وسائط مختلطة', 'منحوتة', 'كولاج', 'رقمي', 'طباعة', 'تركيب'];
        foreach($categories as $index => $category){
            Category::create([
                'name'=> $category,
                'name_ar'=> $categories_ar[$index],
                'cover_image'=> $faker->imageUrl(640, 480, 'art', true),
                'description'=> $faker->sentence,
                'description_ar'=> $faker->sentence,
                'link'=> $faker->url
            ]);
        }
    }
}
