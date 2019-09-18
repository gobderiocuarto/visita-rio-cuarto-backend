<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('groups')->insert(array(
            'name'          => 'Gobierno Abierto',
            'slug'          => 'gobierno_abierto',
            'description'   => '',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')

        ));


        \DB::table('groups')->insert(array(
            'name'          => 'Turismo',
            'slug'          => 'turismo',
            'description'   => '',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));


        \DB::table('groups')->insert(array(
            'name'          => 'Cultura',
            'slug'          => 'cultura',
            'description'   => '',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));


        \DB::table('groups')->insert(array(
            'name'          => 'Deportes',
            'slug'          => 'deportes',
            'description'   => '',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));


        \DB::table('groups')->insert(array(
            'name'          => 'Otras Areas',
            'slug'          => 'otras_areas',
            'description'   => '',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));
        
    }
}
