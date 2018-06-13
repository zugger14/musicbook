<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidColumnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('paid_order');
            $table->string('paypal_payerid')->nullable();
            $table->string('paypal_paymentid')->nullable();
            $table->string('payal_token')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('paid_order');
            $table->dropColumn('paypal_payerid');
            $table->dropColumn('paypal_paymentid');
            $table->dropColumn('paypal_token');
            
        });
    }
}
