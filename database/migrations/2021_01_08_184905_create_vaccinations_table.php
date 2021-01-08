<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->dateTime('date');
            $table->integer('daily_vaccinated');
            $table->timestamps();
        });

        Schema::table('vaccinations', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->dropForeign('vaccinations_region_id_foreign');
        });
        Schema::dropIfExists('vaccinations');
    }
}
