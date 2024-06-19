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
        Schema::create('old_students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('roll_no');
            $table->string('name');
            $table->string('email');
            $table->string('section');
            $table->string('grade');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_students');
    }
};
