<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateUsersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nickname');
                $table->string('stu_id', 15);
                $table->string('password');
                $table->string('class')->nullable();
                $table->string('qq')->nullable();
                $table->string('phone')->nullable();
                $table->string('wx')->nullable();
                $table->string('avatar', 15)->default('default.jpg');
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
            Schema::dropIfExists('users');
        }
    }
