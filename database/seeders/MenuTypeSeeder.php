<?php

namespace Database\Seeders;

use App\Models\MenuType;
use Illuminate\Database\Seeder;

class MenuTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
              $menu_types_seed = [
            ["id"=>1,"caption" => "نوبت ها ", "visible" => 1 ,'icon' => 'home'] ,
            ["id"=>2,"caption" => "اطلاعات پایه" , "visible" => 1 ,'icon' => 'home'] ,
            ["id"=>3,"caption" => " باشگاه مشتریان" , "visible" => 1 ,'icon' => 'home'] ,

            // ["id"=>3,"caption" => "menu_type3" , "visible" => 1  ] ,
            // ["id"=>1,"caption" => "menu_type4" , "visible" => 1 ] ,
            ];


            foreach ($menu_types_seed as $menu_type) {
                
                $already_menu_type = MenuType::where("id", $menu_type["id"])->first();
                if($already_menu_type){
                    $already_menu_type->delete();
                }
                MenuType::create($menu_type);

            }

    }
}
