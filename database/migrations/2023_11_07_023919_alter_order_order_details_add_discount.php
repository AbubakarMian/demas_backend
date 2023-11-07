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
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn(['price','total_price']);
            $table->float('discount')->default(0);
            $table->float('discounted_price',10,2)->default(0);
            $table->float('actual_price',10,2)->default(0);
            $table->float('final_price',10,2)->default(0);
        });
        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->float('discount')->default(0);
            $table->float('discounted_price',10,2)->default(0);
            $table->float('actual_price',10,2)->default(0);
            $table->float('final_price',10,2)->default(0);
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
