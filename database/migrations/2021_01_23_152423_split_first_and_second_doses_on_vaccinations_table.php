<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SplitFirstAndSecondDosesOnVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->dropColumn('daily_vaccinated');
            $table->integer('daily_first_doses')->default(0)->after('date');
            $table->integer('daily_second_doses')->default(0)->after('daily_first_doses');
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
            $table->dropColumn('daily_first_doses');
            $table->dropColumn('daily_second_doses');
            $table->integer('daily_vaccinated')->default(0)->after('date');
        });
    }
}
