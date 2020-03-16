<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHighlightColumnsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->enum('highlight_home_aside', ['home_aside'])->nullable()->after('web');
            $table->enum('highlight', ['emphasize'])->nullable()->after('highlight_home_aside');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('highlight_home_aside');
            $table->dropColumn('highlight');
        });
    }
}
