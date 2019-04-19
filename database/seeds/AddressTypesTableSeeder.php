<?php

use Illuminate\Database\Seeder;

class AddressTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address_types = ['Dirección única','Casa Central', 'Sucursal', 'Oficina', 'Planta Industrial'];

    	foreach ($address_types as $key => $address_type) {

			\DB::table('address_types')->insert(array(

	                'name' => $address_type,
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s')
	        ));
		}
    }
}
