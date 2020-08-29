<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashier');
            $table->double('total');
            $table->timestamps();
        });

        Schema::create('bill_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->integer('bill_id');
            $table->double('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill');
        Schema::dropIfExists('bill_items');
    }
}
