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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('roll_no')->unique();
            $table->string('name');
            $table->string('email');
            $table->enum('status', ['active','dropped_out']);
            $table->unsignedBigInteger('section_id');
            $table->timestamps();
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
