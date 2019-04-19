<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_place', function (Blueprint $table) {
            
            $table->increments('id');

            $table->integer('organization_id')->unsigned();
            $table->integer('place_id')->unsigned();
            $table->tinyinteger('address_type_id')->unsigned()->default(0);
            $table->string('address_type_name')->nullable();
            
            $table->timestamps();

            //relation
            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('place_id')->references('id')->on('places')
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
        Schema::dropIfExists('organization_place');
    }
}
