<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreatePostsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('posts', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('type')->comment("1为丢失 lost");
                $table->integer('user_id')->unsigned();;
                $table->foreign('user_id')->references('id')->on('users');
                $table->string('title', 90);
                $table->string('description', 300);
                $table->string('img', 30)->nullable();
                $table->boolean('stu_card');
                $table->string('card_id', 30)->nullable();
                $table->string('address', 300);
                $table->date('date');
                $table->boolean('solve')->default(0);
                $table->boolean('mark')->default(0);
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
            Schema::dropIfExists('posts');
        }
    }
