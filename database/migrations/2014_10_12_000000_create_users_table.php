<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('fname');
            $table->string('lname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('email')->unique();
            $table->tinyInteger('isVerified')->default(0);
            $table->string('password');
            $table->string('contact');
            $table->string('img');
            $table->tinyInteger('role');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
