<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description")->nullable();
            $table->string("banner")->nullable();
            $table->string("icon")->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id')->index();
            $table->string("title");
            $table->text("description")->nullable();
            $table->timestamps();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->string("title")->nullable();
            $table->text("short_description")->nullable();
            $table->text("description")->nullable();
            $table->float("price");
            $table->boolean("is_hot")->default(0);
            $table->boolean("is_new")->default(0);
            $table->timestamps();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->string("url");
            $table->timestamps();
        });
        Schema::table('images', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->text("short_description")->nullable();
            $table->float("price");
            $table->string("type")->default('abs');
            $table->timestamps();
        });

        Schema::create("option_product", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('option_id')->index();
            $table->unsignedBigInteger('product_id')->index();

            $table->timestamps();
        });
        Schema::table('option_product', function (Blueprint $table) {
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->string("hash")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create("order_product", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->timestamps();
        });
        Schema::table('order_product', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('option_product');
        Schema::dropIfExists('options');
        Schema::dropIfExists('images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('games');
    }
}
