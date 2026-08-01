<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories_seed = [
            ["caption" => "category_item_1" , "is_active" => true ] ,
            ["caption" => "category_item_2" , "is_active" => true ] ,
            ["caption" => "category_item_3" , "is_active" => true ] ,
            ["caption" => "category_item_4" , "is_active" => true ] ,
            ];


            foreach ($categories_seed as $category) {

                $already_category = Category::where("caption", $category["caption"])->first();
                if($already_category){
                    $already_category->delete();
                }
                Category::create($category);

            }
    }
}
