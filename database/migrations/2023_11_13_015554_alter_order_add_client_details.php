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
            $table->string('customer_name')->nullable()->default(null);
            $table->string('customer_number')->nullable()->default(null);
            $table->float('customer_collection_price',10,2)->default(0);
        });
        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropColumn('collection_amount_from_user');
            $table->float('customer_collection_price',10,2)->default(0);
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
