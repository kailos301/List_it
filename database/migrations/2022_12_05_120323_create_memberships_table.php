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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->double('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('status')->default(0);
            $table->tinyInteger('is_trial')->default(0);
            $table->integer('trial_days')->default(0);
            $table->longText('receipt')->nullable();
            $table->longText('transaction_details')->nullable();
            $table->longText('settings')->nullable();
            $table->bigInteger('package_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->tinyInteger('modified')->nullable()->comment('1 - modified by Admin, 0 - not modified by Admin');
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
        Schema::dropIfExists('memberships');
    }
};
