<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionsToProductOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("order_product_option", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_product_id')->index();
            $table->unsignedBigInteger('option_id')->index();
            $table->timestamps();
        });
        Schema::table('order_product_option', function (Blueprint $table) {
            $table->foreign('order_product_id')->references('id')->on('order_product')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("order_product_option");
    }
}
