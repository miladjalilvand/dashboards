<?php

namespace Database\Seeders;

use App\Models\MenuPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): vomenu_id
    {
        //


        $permissions = [
            ['menu_id' => 1 , 'permission_id' =>2],

            ['menu_id' => 2 ,
                'permission_id' =>2],

        ['menu_id' => 3 ,
            'permission_id' =>1 ],

        ['menu_id' => 4 ,
            'permission_id' =>1],
        ['menu_id' => 5 ,
            'permission_id' =>1 ],

        ['menu_id' => 6 ,
            'permission_id' =>1],

        ['menu_id' => 6 ,
            'permission_id' =>1],

 ['menu_id' => 10 ,
            'permission_id' =>4],
            ['menu_id' => 7 ,
            'permission_id' =>2],
            ['menu_id' => 9 ,
            'permission_id' =>2],
            ['menu_id' => 8 ,
            'permission_id' =>2],


        ];

        foreach($permissions as $permission){
            MenuPermission::updateOrCreate([
                'menu_id' => $permission['menu_id'] ,
                'permission_id' => $permission['permission_id'],

            ]);
        }



    }
}
