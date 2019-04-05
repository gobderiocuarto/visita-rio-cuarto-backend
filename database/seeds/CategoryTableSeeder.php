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

    	foreach ($categories as $key => $category) {

			\DB::table('categories')->insert(array(

	                'name' => $category,
	                'category_id' => 0,
	                'slug'  => Str::slug($category),
                    'state' => 1,
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s')
	        ));

            /*

            foreach ($category as $key => $subcategory) {
                \DB::table('categories')->insert(array(

                    'name' => $subcategory,
                    'category_id' => 0,
                    'slug'  => Str::slug($subcategory),
                    'state' => 1,
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s')
                ));
            }
            */

		}
    }
}
