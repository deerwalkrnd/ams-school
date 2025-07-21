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
        Schema::create('attendance_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->date('date');
            $table->tinyInteger('status')->default(0)->comment('0 = not taken, 1 = taken');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['teacher_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_status');
    }
};
