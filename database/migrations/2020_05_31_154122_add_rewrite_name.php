<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRewriteName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("games",function (Blueprint $t){
           $t->string("rewrite");
        });
        Schema::table("products",function (Blueprint $t){
            $t->string("rewrite");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("games",function (Blueprint $t){
            $t->dropColumn("rewrite");
        });
        Schema::table("products",function (Blueprint $t){
            $t->dropColumn("rewrite");
        });
    }
}
