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
            ['caption' => 'بنر معرفی' , 'slug' => 'opt_banner'] ,
            [ 'caption' => 'درباره ما' , 'slug' => 'opt_aboutus'],
            [ 'caption' => 'نمونه کار ها' , 'slug' => 'samples'] ,
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
