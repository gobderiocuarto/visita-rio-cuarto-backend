<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizationables', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('organization_id')->unsigned();
            $table->string('organizationable_type');
            $table->integer('organizationable_id')->unsigned();

            $table->tinyinteger('address_type_id')->unsigned()->default(0);
            $table->string('address_type_name')->nullable();

            $table->string('apartament')->nullable();

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
        Schema::dropIfExists('organizationables');
    }
}
