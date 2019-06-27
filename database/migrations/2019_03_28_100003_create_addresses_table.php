<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('street_id')->unsigned();
            $table->integer('number')->unsigned()->nullable(); // Sin Numero?? Km tal??
            
            $table->string('floor')->nullable();
            $table->float('lat', 10,6)->nullable();
            $table->float('lng', 10,6)->nullable();
            $table->integer('zone_id')->unsigned()->nullable()->default(0);

            $table->timestamps();

            $table->unique(['street_id', 'number']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
