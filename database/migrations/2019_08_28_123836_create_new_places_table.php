<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewPlacesTable extends Migration
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

            $table->integer('organization_id')->unsigned();
            $table->string('placeable_type');
            $table->integer('placeable_id')->unsigned();

            $table->tinyinteger('address_type_id')->unsigned()->default(0);
            $table->string('address_type_name')->nullable();

            $table->string('apartament')->nullable();
            $table->timestamps();

            //relation
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
        Schema::dropIfExists('places');
    }
}
