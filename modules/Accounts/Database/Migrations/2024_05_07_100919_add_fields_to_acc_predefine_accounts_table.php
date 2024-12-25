<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acc_predefine_accounts', function (Blueprint $table) {
            $table->integer('prov_npf_code')->nullable();
            $table->integer('emp_npf_contribution')->nullable();
            $table->integer('empr_npf_contribution')->nullable();
            $table->integer('emp_icf_contribution')->nullable();
            $table->integer('empr_icf_contribution')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acc_predefine_accounts', function (Blueprint $table) {
            $table->dropColumn('prov_state_tax');
            $table->dropColumn('prov_npf_code');
            $table->dropColumn('emp_npf_contribution');
            $table->dropColumn('empr_npf_contribution');
            $table->dropColumn('emp_icf_contribution');
            $table->dropColumn('empr_icf_contribution');
        });
    }
};
