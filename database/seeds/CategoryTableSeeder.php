<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Str as Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Alojamiento','Gastronomía', 'Esparcimiento', 'Transporte'];

    	foreach ($categories as $key => $name) {

			\DB::table('categories')->insert(array(

	                'name' => $name,
	                'category_id' => 0,
	                'slug'  => Str::slug($name),
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s')
	        ));
		}
    }
}
