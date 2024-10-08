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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->date('date');
            $table->float('total');
            $table->float('first_payment');
            $table->float('balance');
            $table->foreignId('route_id')->references('id')->on('routes');
            $table->foreignId('billing_schedule_id')->references('id')->on('billing_schedules');
            $table->foreignId('credit_status_id')->references('id')->on('credit_statuses');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits');
    }
};
