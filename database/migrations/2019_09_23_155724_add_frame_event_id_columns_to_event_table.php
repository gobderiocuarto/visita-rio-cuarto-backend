<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFrameEventIdColumnsToEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('frame', ['is-frame'])->nullable();
            $table->integer('event_id')->unsigned()->nullable()->after('group_id');

            //relation
            $table->foreign('event_id')->references('id')->on('events')
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
        
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_event_id_foreign');
            $table->dropColumn('event_id');
            $table->dropColumn('frame');
        });
    }
}
