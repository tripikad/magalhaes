<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_et');
            $table->string('iso2')->nullable();
            $table->integer('id_legacy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('target_countries');
    }
}
