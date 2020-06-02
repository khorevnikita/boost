<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("banners", function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger("game_id")->index()->nullable();
            $t->string("background")->nullable();
            $t->string("object_image")->nullable();
            $t->string("text")->nullable();
            $t->string("action_title")->nullable();
            $t->string("action_url")->nullable();
            $t->boolean("published")->default(0);
            $t->timestamps();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
