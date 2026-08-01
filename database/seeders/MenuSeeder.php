<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
                $menus_seed = [
            ["caption" => "نمایش همه نوبت ها" ,'icon' => 'home', "visible" => 1 , "menu_type_id" => 1 ,"slug" => "reserves" ,] ,
            ["caption" => " داشبرد نوبت ها" ,'icon' => 'home', "visible" => 1 , "menu_type_id" => 1 ,"slug" => "reserves_dashboard" ,] ,
            ["caption" => "شعبه ها",'icon' => 'home' , "visible" => 1 ,"menu_type_id"  => 2,"slug" => "branches" , ] ,
            ["caption" => "دسنه بندی",'icon' => 'home' , "visible" => 1 ,"menu_type_id"  => 2 ,"slug" => "categories" ,] ,
            ["caption" => "سرویس ها" ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 2 ,"slug" => "services" ,] ,
            ["caption" => " کارمندان" ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 2 ,"slug" => "employees" ,] ,
             ["caption" => " وب سایت" ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 3 ,"slug" => "new-reserve" ,] ,
            ["caption" => " مشتریان" ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 3 ,"slug" => "customers" ,] ,
             ["caption" => "پرداخت  ها" ,'icon' => 'home', "visible" => 1 ,"menu_type_id"  => 3 ,"slug" => "payments" ,] ,

            ];


            foreach ($menus_seed as $menu) {

                $already_menu = Menu::where("caption", $menu["caption"])->first();
                if($already_menu){
                    $already_menu->delete();
                }
                Menu::create($menu);

            }
    }
}
