<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [
            ['caption' => 'MANAGER'] ,
           [ 'caption' => 'ADMIN' ,],
           [ 'caption' => 'CUSTOMER'] ,
        ];


        foreach ($roles as $role) {
            $already_item = Role::where('caption', $role)->exists();
            if(!$already_item) {
                Role::create([
                    'caption' => $role['caption'],
                ]);
            }
        }
    }
}
