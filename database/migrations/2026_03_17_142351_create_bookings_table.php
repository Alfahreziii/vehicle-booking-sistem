<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique()->comment('Format: VBS-YYYYMMDD-XXXX');
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();
            $table->string('purpose');
            $table->text('description')->nullable();
            $table->string('destination');
            $table->integer('passenger_count')->default(1);
            $table->datetime('departure_at');
            $table->datetime('return_at');
            $table->integer('odometer_start')->nullable();
            $table->integer('odometer_end')->nullable();
            $table->enum('status', [
                'pending',      // Baru dibuat, menunggu approval
                'in_review',    // Sedang dalam proses approval
                'approved',     // Semua level sudah setuju
                'rejected',     // Salah satu level menolak
                'in_use',       // Sedang digunakan
                'completed',    // Selesai dikembalikan
                'cancelled',    // Dibatalkan pemohon
            ])->default('pending');
            $table->integer('total_approver_levels')->default(2);
            $table->integer('current_approval_level')->default(0);
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'departure_at']);
            $table->index('requester_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
