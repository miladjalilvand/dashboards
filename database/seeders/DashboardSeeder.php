<?php

namespace Database\Seeders;

use App\Models\Dashboard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $dashboards = [
            [
                'id' => 1,
                'percentage' => 90,
                'description' => 'سامانه نوبت دهی برای سالن های زیبایی ',
                'caption' => 'نوبت دهی',
                'per_of_month' => 700000
            ]
        ];

        foreach ($dashboards as $item) {
            $exist_item = Dashboard::find($item['id']);

            if($exist_item){
                $exist_item->update($item);
            }
            else{
                Dashboard::create($item);
            }
        }

    }
}
