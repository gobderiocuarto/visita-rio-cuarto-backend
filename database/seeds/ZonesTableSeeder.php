<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Str as Str;

class ZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       //factory(App\Zone::class)->create();

    	$zones = ['Banda Norte','Centro', 'Alberdi', 'Bimaco', 'Sur', 'Oeste'];

    	foreach ($zones as $key => $name) {

			\DB::table('zones')->insert(array(

	                'name' => $name,
	                'slug'  => Str::slug($name),
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s')
	        ));
		}

    }
}
