<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::Create ([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'WebMaster Administrador del sistema',
            'special' => 'all-access'
        ]);
    }
}
