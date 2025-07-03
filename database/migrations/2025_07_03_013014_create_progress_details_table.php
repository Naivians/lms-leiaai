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
        Schema::create('progress_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progress_id')->constrained("assessment_progress")->onDelete("cascade");
            $table->integer("qid");
            $table->integer("cid");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_details');
    }
};
