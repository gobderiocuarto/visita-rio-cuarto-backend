<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('event_id')->unsigned();

            $table->date('start_date');
            $table->time('start_time');

            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();

            $table->string('observations', 256)->nullable();

            $table->tinyinteger('state')->unsigned()->default(1);
            
            $table->timestamps();

            //relation
            $table->foreign('event_id')->references('id')->on('events')
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
        Schema::dropIfExists('calendars');
    }
}
