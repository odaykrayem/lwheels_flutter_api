<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('name')->nullable();
            $table->string('f_name')->default('new');
            $table->string('l_name')->default('new');
            $table->string('phone')->unique()->default('00112233');
            $table->string('email')->unique()->nullable();
            $table->string('ref_code')->unique()->default('xxxx');
            $table->integer('ref_times')->default(0);
            $table->string('password');
            $table->integer('points')->default(0);
            $table->double('balance')->default(0);
            $table->timestamp('email_verified_at')->nullable();
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
};
