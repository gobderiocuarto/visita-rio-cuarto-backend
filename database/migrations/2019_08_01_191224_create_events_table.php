<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('place_id')->unsigned()->nullable();

            $table->string('title', 128);
            $table->string('slug', 128)->unique();
            $table->string('summary', 512);
            $table->Text('description')->nullable();
            $table->string('organizer', 256)->nullable();

            $table->tinyinteger('state')->unsigned()->default(0);

            $table->timestamps();

            //relation
            // $table->foreign('place_id')->references('id')->on('places')
            //     ->onDelete('set null')
            //     ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
