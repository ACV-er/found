<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('losts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('announcer', 15);
            $table->string('title', 90);
            $table->string('description', 300);
            $table->string('img', 30);
            $table->boolean('stu_card');
            $table->string('card_id', 30)->nullable();
            $table->string('address', 300);
            $table->date('date');
            $table->boolean('solve');
            $table->timestamps();
            $table->foreign('announcer')->references('stu_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('losts');
    }
}
