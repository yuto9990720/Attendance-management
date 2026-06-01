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
            Schema::create('stamp_correction_rest_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stamp_correction_request_id')->constrained();
            $table->time('rest_start')->nullable();
            $table->time('rest_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stamp_correction_rest_times');
    }
};
