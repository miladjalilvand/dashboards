<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\MenuType;
use App\Models\Panel;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $seeders = [
            CategorySeeder::class,
            MenuTypeSeeder::class,
            MenuSeeder::class,
            CustomerMenuSeeder::class,
            DashboardSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            $this->call($seeder);
        }

        // User::destroy(1);
        $user = User::create(
            [
                'name' => 'milad' ,
                'email' => 'admin@email.com' ,
                'password' => 'password' ,
                'mobile_number' => '09133333333',
                'role_id' => 1
            ]
        );

        Auth::login($user);

        // Panel::destroy(1);
       $panel =  Panel::create([
            'id' => 1 ,
            'website' => '---',
            'expired_date' => now() ,
            'user_id' => userAuth()->id,
           'dashboard_id' => 1
        ]);

        // Role::destroy(1);
       $role =  Role::create([
            'id' => 1,
           'caption' => 'system admin'
        ]);


        // Admin::destroy(1);
        Admin::create([
            'role_id' => $role->id ,
            'panel_id' =>  $panel->id ,
            'user_id' => userAUTH()->id,
            'password' => '1234'
        ]);


        $statuses = [
            ['id' => 1 ,
            'caption' => ' درحال بررسی نوبت'],
            ['id' => 2 ,
            'caption' => 'نوبت ثبت و تایید شده '],

        ];

        foreach($statuses as $status){
            Status::updateOrCreate([
                'id' => $status['id'] ,
                'caption' => $status['caption']
            ]);
        }




    }
}
