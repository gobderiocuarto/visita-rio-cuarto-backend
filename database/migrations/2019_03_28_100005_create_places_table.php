<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('address_id')->unsigned();

            $table->string('name', 128);
            $table->string('slug', 128)->unique();
            $table->text('description')->nullable();

            $table->tinyinteger('state')->unsigned()->default(1);           

            //$table->float('lat', 10,6)->nullable();
            //$table->float('lng', 10,6)->nullable();
            //$table->integer('street')->unsigned()->nullable();
            //$table->integer('number')->unsigned()->nullable(); // Sin Numero?? Km tal??
            //$table->string('floor', 64)->nullable();
            //$table->string('zone', 64)->nullable();

            $table->timestamps();

            //relation
            
            $table->foreign('address_id')->references('id')->on('addresses')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
