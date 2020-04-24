<?php

use Illuminate\Database\Seeder;

class AddressIdInPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = \DB::table('places')->get();

        foreach ($places as $key => $place) {
            \DB::table('places')->where('id', $place->id )->update(array(
                'address_id' => $place->placeable_id,
            ));
        }
    }
}
