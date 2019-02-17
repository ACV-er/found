<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('founds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();;
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title', 90);
            $table->string('description', 300);
            $table->string('img', 30)->nullable();
            $table->boolean('stu_card');
            $table->string('card_id', 30)->nullable();
            $table->string('address', 300);
            $table->date('date');
            $table->boolean('solve');
            $table->boolean('mark');
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
        Schema::dropIfExists('founds');
    }
}
