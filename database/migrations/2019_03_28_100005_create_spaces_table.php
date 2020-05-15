<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaces', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('category_id')->unsigned()->default(0);
            $table->integer('address_id')->unsigned();

            $table->string('name', 128);
            $table->string('slug', 128)->unique();
            $table->text('description')->nullable();

            $table->tinyinteger('state')->unsigned()->default(1);           

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
        
        Schema::table('spaces', function (Blueprint $table) {
            $table->dropUnique('spaces_slug_unique');
            $table->dropForeign('spaces_address_id_foreign');
        });

        Schema::dropIfExists('spaces');
    }
}
