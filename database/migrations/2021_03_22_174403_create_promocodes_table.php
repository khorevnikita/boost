<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->integer("value");
            $table->string("currency");
            $table->timestamp("end_at")->nullable();
            $table->string("code");
            $table->timestamps();
        });

        Schema::table("orders", function (Blueprint $table) {
            $table->unsignedBigInteger('promocode_id')->index()->nullable();
            $table->foreign('promocode_id')->references('id')->on('promocodes')->onDelete('set null');
        });

        Schema::table("products", function (Blueprint $table) {
            $table->string("currency")->nullable()->after("price");
            $table->text("requirements")->nullable();
            $table->string("seo_title")->nullable();
            $table->text("seo_description")->nullable();
            $table->text("seo_keys")->nullable();
        });

        Schema::create("scripts", function (Blueprint $table) {
            $table->id();
            $table->string("place")->default("header");
            $table->longText("value")->nullable();
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
        Schema::dropIfExists('scripts');


        Schema::table("products", function (Blueprint $table) {
            $table->dropColumn("currency");
            $table->dropColumn("requirements");
            $table->dropColumn("seo_title");
            $table->dropColumn("seo_description");
            $table->dropColumn("seo_keys");
        });
        Schema::table("orders", function (Blueprint $table) {

            $table->dropForeign('orders_promocode_id_foreign');#->references('id')->on('promocodes')->onDelete('set null');
            $table->dropColumn('promocode_id');#->index()->nullable();
        });
        Schema::dropIfExists('promocodes');
    }
}
