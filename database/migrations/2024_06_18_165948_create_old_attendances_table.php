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
        Schema::create('old_attendances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('student_name');
            $table->string('teacher_name');
            $table->string('grade_name');
            $table->string('section_name');
            $table->integer('present');
            $table->integer('absent');
            $table->string('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_attendances');
    }
};
