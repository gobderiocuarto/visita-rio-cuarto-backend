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

    	$zones = ['Banda Norte','Centro', 'Alberdi', 'Bimaco'];

    	foreach ($zones as $key => $name) {

			\DB::table('zones')->insert(array(

	                'name' => $name,
	                'slug'  => Str::slug($name)
	        ));
		}

    }
}
