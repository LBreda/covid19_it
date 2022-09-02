<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecondBoosterToVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->integer('daily_second_boosters')->default(0)->after('daily_first_boosters');
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
            $table->dropColumn('daily_second_boosters');
        });
    }
}
