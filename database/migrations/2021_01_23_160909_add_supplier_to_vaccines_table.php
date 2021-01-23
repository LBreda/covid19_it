<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierToVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->unsignedBigInteger('vaccine_supplier_id')->nullable()->after('region_id');
        });

        Schema::table('vaccinations', function (Blueprint $table) {
            $table->foreign('vaccine_supplier_id')->references('id')->on('vaccine_suppliers');
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
            $table->dropForeign('vaccines_vaccine_supplier_id_foreign');
        });

        Schema::table('vaccinations', function (Blueprint $table) {
            $table->dropColumn('vaccine_supplier_id');
        });
    }
}
