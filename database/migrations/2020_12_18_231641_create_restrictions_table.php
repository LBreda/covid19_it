<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restrictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restriction_type_id');
            $table->unsignedBigInteger('region_id');
            $table->date('since');
            $table->date('until')->nullable();
            $table->timestamps();
        });

        Schema::table('restrictions', function (Blueprint $table) {
            $table->foreign('restriction_type_id')->references('id')->on('restriction_types');
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
        Schema::table('restrictions', function (Blueprint $table) {
            $table->dropForeign('restrictions_restriction_type_id_foreign');
            $table->dropForeign('restrictions_region_id_foreign');
        });
        Schema::dropIfExists('restrictions');
    }
}
