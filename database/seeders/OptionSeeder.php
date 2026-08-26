<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $options = [
            ['id' => 1 ,  'caption' => 'بنر معرفی' , 'slug' => 'opt_banner'] ,
            ['id' => 2 , 'caption' => 'درباره ما' , 'slug' => 'opt_aboutus'],
            ['id' => 3 , 'caption' => 'نمونه کار ها' , 'slug' => 'panel_portofolios'] ,
        ];


        foreach ($options as $option) {
            $already_item = Option::where('caption', $option['caption'])->exists();
            if(!$already_item) {
                Option::create([
                    'caption' => $option['caption'],
                    'slug' => $option['slug'],
                ]);
            }
        }
    }
}
