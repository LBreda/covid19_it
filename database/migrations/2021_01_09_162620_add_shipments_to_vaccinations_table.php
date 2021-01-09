<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShipmentsToVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->integer('daily_vaccinated')->default(0)->change();
            $table->integer('daily_shipped')->default(0)->after('daily_vaccinated');
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
            //
        });
    }
}
