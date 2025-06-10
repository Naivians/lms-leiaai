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
        Schema::table('classes', function (Blueprint $table) {
            Schema::table('classes', function (Blueprint $table) {
                // 1. Drop the foreign key and column for user_id
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
                // 2. Modify the default value for 'active' column
                $table->tinyInteger('active')->default(1)->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            //
        });
    }
};
