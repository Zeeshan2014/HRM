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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['meeting', 'weekend', 'wfh', 'overtime', 'leave']);
            $table->timestamp('start_time')->nullable(); // For clock_in or meeting start
            $table->timestamp('end_time')->nullable();   // For clock_out or meeting end
            $table->text('reason')->nullable();          // For overtime/leave reasons
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('total_hours')->nullable();  // Total hours for work
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
