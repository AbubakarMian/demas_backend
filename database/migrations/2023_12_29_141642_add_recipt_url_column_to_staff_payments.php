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
        Schema::table('staff_payments', function (Blueprint $table) {
                $table->string('receipt_url',300)->nullable()->default(null);
                $table->bigInteger('staf_payment_incomming_id')->nullable()->default(0);
            });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_payments', function (Blueprint $table) {
            //
        });
    }
};
