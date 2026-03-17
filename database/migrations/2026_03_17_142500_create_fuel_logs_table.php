<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('filled_by')->constrained('users')->cascadeOnDelete();
            $table->decimal('liters', 8, 2);
            $table->decimal('cost_per_liter', 10, 2);
            $table->decimal('total_cost', 12, 2);
            $table->integer('odometer_before');
            $table->integer('odometer_after');
            $table->date('log_date');
            $table->string('fuel_station')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};
