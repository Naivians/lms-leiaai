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
            $table->string('id_number')->nullable();
            $table->tinyInteger('class_id')->nullable();
            $table->string('name');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->tinyInteger('gender')->default(0);
            $table->string('contact');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('img');
            $table->tinyInteger('role')->default(0);
            $table->string('verification_token')->nullable()->unique();
            $table->tinyInteger('isVerified')->default(0);
            $table->tinyInteger('login_status')->default(0);
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
