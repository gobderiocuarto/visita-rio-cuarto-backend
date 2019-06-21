<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('category_id')->unsigned();

            $table->string('name', 128);
            $table->string('slug', 128)->unique();
            $table->Text('description')->nullable();

            $table->tinyinteger('state')->unsigned()->default(1);

            $table->string('email', 128)->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('web')->nullable();

            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')
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
        Schema::dropIfExists('organizations');
    }
}
