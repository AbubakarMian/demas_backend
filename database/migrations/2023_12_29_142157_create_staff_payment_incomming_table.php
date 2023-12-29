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
        Schema::create('staff_payment_incomming', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->default(0);
            $table->string('staff_type',300)->nullable()->default(0);
            $table->string('amount',300)->nullable()->default(0);
            $table->string('payment_type', 100)->nullable()->default(null); //recieved / paid
            $table->string('detail', 500)->nullable()->default(null);
            $table->json('receipt_url')->nullable()->default(null);
            $table->string('verification_status',300)->nullable()->default(null);
            $table->softDeletes();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_payment_incomming');
    }
};
