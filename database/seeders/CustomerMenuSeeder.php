<?php

namespace Database\Seeders;

use App\Models\CustomerMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
                        $menus_seed = [
            ["caption" => "نمایش همه نوبت ها" ,'icon' => 'home', "visible" => 1 , "menu_type_id" => 1 ,"slug" => "reservesc" ,] ,
             ["caption" => " نوبت جدید " ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 1 ,"slug" => "new-reservec" ,] ,
            ["caption" => " پروفایل" ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 2 ,"slug" => "profile" ,] ,
             ["caption" => "پرداخت  ها" ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 3 ,"slug" => "paymentsc" ,] ,

            ];


            foreach ($menus_seed as $menu) {
                
                $already_menu = CustomerMenu::where("caption", $menu["caption"])->first();
                if($already_menu){
                    $already_menu->delete();
                }
                CustomerMenu::create($menu);

            }
    }
}
