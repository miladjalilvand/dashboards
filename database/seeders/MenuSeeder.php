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
    [
        "caption" => "نمایش همه نوبت‌ها",
        "icon" => "calendar-days",
        "visible" => 1,
        "menu_type_id" => 1,
        "slug" => "reserves",
    ],
    [
        "caption" => "داشبورد نوبت‌ها",
        "icon" => "squares-2x2",
        "visible" => 1,
        "menu_type_id" => 1,
        "slug" => "reserves_dashboard",
    ],
    [
        "caption" => "شعبه‌ها",
        "icon" => "building-office-2",
        "visible" => 1,
        "menu_type_id" => 2,
        "slug" => "branches",
    ],
    [
        "caption" => "دسته‌بندی‌ها",
        "icon" => "tag",
        "visible" => 1,
        "menu_type_id" => 2,
        "slug" => "categories",
    ],
    [
        "caption" => "سرویس‌ها",
        "icon" => "sparkles",
        "visible" => 1,
        "menu_type_id" => 2,
        "slug" => "services",
    ],
    [
        "caption" => "کارمندان",
        "icon" => "users",
        "visible" => 1,
        "menu_type_id" => 2,
        "slug" => "employees",
    ],
    [
        "caption" => "نوبت جدید",
        "icon" => "plus-circle",
        "visible" => 1,
        "menu_type_id" => 3,
        "slug" => "new-reserve",
    ],
    [
        "caption" => "مشتریان",
        "icon" => "user-group",
        "visible" => 1,
        "menu_type_id" => 3,
        "slug" => "customers",
    ],
    [
        "caption" => "پرداخت‌ها",
        "icon" => "credit-card",
        "visible" => 1,
        "menu_type_id" => 3,
        "slug" => "payments",
    ],
];

        Menu::truncate();

            foreach ($menus_seed as $menu) {

//                $already_menu = Menu::where("caption", $menu["caption"])->first();
//                if($already_menu){
//                    $already_menu->delete();
//                }
                Menu::create($menu);

            }
    }
}
