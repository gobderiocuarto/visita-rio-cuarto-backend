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

            $table->integer('organization_id')->unsigned()->nullable();
            $table->string('address_type', 64);
            $table->integer('street')->unsigned();
            $table->mediumInteger('number')->unsigned();
            $table->string('floor', 64)->nullable();
            $table->string('zone', 64)->nullable();
            $table->float('lat', 10,6)->nullable();
            $table->float('lng', 10,6)->nullable();

            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onDelete('cascade')
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
        Schema::dropIfExists('addresses');
    }
}
