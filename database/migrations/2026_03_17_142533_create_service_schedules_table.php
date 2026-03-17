<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('service_type')->comment('Oli, ban, tune up, dll');
            $table->date('scheduled_date');
            $table->integer('scheduled_odometer')->nullable();
            $table->text('description')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'overdue'])->default('scheduled');
            $table->date('completed_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_schedules');
    }
};
