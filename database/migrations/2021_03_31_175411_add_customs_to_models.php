<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomsToModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string("button_icon")->nullable();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->text("colors")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn("colors");
        });
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn("button_icon");
        });
    }
}
