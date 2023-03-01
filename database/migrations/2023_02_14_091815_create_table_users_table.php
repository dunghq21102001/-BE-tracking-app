<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->notNullable();
            $table->string('password');
            $table->string('first_name')->default('');
            $table->string('last_name')->default('');
            $table->string('phone1')->default('');
            $table->string('phone2')->default('');
            $table->string('city')->default('');
            $table->string('country')->default('');
            $table->string('address')->default('');
            $table->text('api_token')->default('');
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
        Schema::dropIfExists('table_users');
    }
}
