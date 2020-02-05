<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlaceIdToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->integer('place_id')->unsigned()->nullable()->after('id');

            //relation
            $table->foreign('place_id')->references('id')->on('places')
            ->onDelete('set null')
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
        Schema::table('places', function (Blueprint $table) {
            $table->dropForeign('places_place_id_foreign');
            $table->dropColumn('place_id');
        });
    }
}
