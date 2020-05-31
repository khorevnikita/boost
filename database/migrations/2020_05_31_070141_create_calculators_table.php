<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id")->index();
            $table->string("name")->nullable();
            $table->text("description")->nullable();
            $table->string("min_title")->nullable();
            $table->integer("min_value")->default(0);
            $table->string("max_title")->nullable();
            $table->integer("max_value")->default(100);
            $table->integer("step")->default(1);
            $table->string("step_type")->default("abs");
            $table->integer("step_price")->default(1);
            $table->integer("start_value")->nullable();
            $table->timestamps();
        });
        Schema::table('calculators', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculators');
    }
}
