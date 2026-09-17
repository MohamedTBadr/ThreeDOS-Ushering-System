<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('college');
            $table->string('level', 50);
            $table->string('council', 100)->nullable();
            $table->string('event_type', 100)->nullable();
            $table->string('ushered_by')->nullable();
            $table->enum('rating', ['Pending', 'Acceptance', 'B', 'Rejection'])->default('Pending');
            $table->text('notes')->nullable();
            $table->dateTime('interview_time')->nullable();
            $table->string('interviewed_by', 100)->nullable();
            $table->longText('interview_questions')->nullable();
            $table->json('interview_notes')->nullable();
            $table->timestamps();

            $table->index('rating');
            $table->index('level');
            $table->index('council');
            $table->index('email');
        });

        // Alias table 'registration' for legacy compatibility (if needed, view)
        // Legacy code used `registration` singular; we support both via model table property
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
