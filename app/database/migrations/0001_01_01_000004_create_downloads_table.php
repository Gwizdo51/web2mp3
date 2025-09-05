<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('downloads', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('youtube_url', 255);
            $table->string('format', 10);
            $table->unsignedTinyInteger('quality');
            $table->string('file_name', 255)->nullable();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('downloads');
    }
};
