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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');
            $table->integer('present');
            $table->integer('absent');
            $table->string('comment', 255)->default('');
            $table->date('date')->default(date('Y-m-d'));
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('teacher_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
