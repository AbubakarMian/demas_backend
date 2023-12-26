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
        Schema::table('users', function (Blueprint $table) {
            $table->double('wallet', 20, 2)->nullable()->default(0);
        });
        Schema::table('staff_payments', function (Blueprint $table) {
            $table->string('payment_type', 100)->nullable()->default(null); //recieved / paid
            $table->string('detail', 500)->nullable()->default(null);
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
