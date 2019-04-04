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

            $table->string('name', 128);
            $table->string('slug', 128)->unique();
            $table->text('description')->nullable();

            $table->float('lat', 10,6)->nullable();
            $table->float('lng', 10,6)->nullable();

            $table->integer('street')->unsigned();
            $table->mediumInteger('number')->unsigned();
            $table->string('floor', 64)->nullable();
            $table->string('zone', 64)->nullable();

            $table->tinyinteger('state')->unsigned()->default(1);           

            $table->timestamps();
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
