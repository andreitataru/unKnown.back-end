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
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            //
            $table->date('datebirth')->nullable();
            $table->string('gender')->nullable();
            $table->string('bio')->nullable();
            $table->mediumText('picture')->nullable();
            $table->string('work')->nullable();
            $table->string('place')->nullable();
            $table->string('school')->nullable();
            //
            $table->decimal('latitude', 10,7)->default(0);
            $table->decimal('longitude', 10,7)->default(0);
            $table->tinyInteger('discovery')->default(1);
            //
            $table->tinyInteger('verified')->default(0);
            $table->tinyInteger('fbUser')->default(0);
            $table->tinyInteger('AccCreationStep')->default(0);
            $table->string('email_token')->nullable();
            $table->string('email_code')->nullable();
            //
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
