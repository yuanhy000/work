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
            $table->bigIncrements('id');
            $table->string('number', 9)->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 11)->unique()->nullable();
            $table->string('openid')->unique()->nullable();
            $table->string('avatar')->default('https://chatting-storage.oss-cn-beijing.aliyuncs.com/default/default-user.svg');
            $table->integer('sex')->default(1);
            $table->integer('age')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('signature')->nullable();
            $table->date('birth')->default('2000-01-01')->nullable();
            $table->unsignedBigInteger('zodiac_id')->default('1')->index()->nullable();
            $table->unsignedBigInteger('constellation_id')->default('1')->index()->nullable();
            $table->string('blood_type')->default('A')->nullable();
            $table->string('address')->nullable();
            $table->string('hometown')->nullable();
            $table->boolean('status')->default(false)->nullable();
            $table->string('school')->nullable();
            $table->rememberToken();
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
