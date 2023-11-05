<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travel_agent', function (Blueprint $table) {
            $table->float('credit_amount',10,2)->nullable()->default(0);
        });
    
        Schema::table('sale_agent', function (Blueprint $table) {
            $table->float('credit_amount',10,2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
