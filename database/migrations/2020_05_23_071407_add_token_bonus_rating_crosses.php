<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenBonusRatingCrosses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->string("confirmation_token")->nullable();
            $table->float("bonus")->nullable();
        });

        Schema::table("orders", function (Blueprint $table) {
            $table->float("amount")->default(0);
        });

        Schema::create("assessments", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->float("value")->nullable();
            $table->timestamps();
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create("crosses", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_product_id')->index();
            $table->unsignedBigInteger('remote_product_id')->index();
            $table->timestamps();
        });

        Schema::table('crosses', function (Blueprint $table) {
            $table->foreign('original_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('remote_product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crosses');
        Schema::dropIfExists("assessments");
        Schema::table("orders", function (Blueprint $table) {
            $table->dropColumn("amount");
        });
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("bonus");
            $table->dropColumn("confirmation_token");
        });
    }
}
