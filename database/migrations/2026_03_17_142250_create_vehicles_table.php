<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->enum('type', ['passenger', 'cargo'])->comment('passenger=angkutan orang, cargo=angkutan barang');
            $table->enum('ownership', ['owned', 'rented'])->default('owned');
            $table->string('rental_company')->nullable()->comment('Nama perusahaan sewaan jika rented');
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'inactive'])->default('available');
            $table->decimal('fuel_consumption', 5, 2)->nullable()->comment('km per liter');
            $table->integer('current_odometer')->default(0)->comment('dalam km');
            $table->date('last_service_date')->nullable();
            $table->integer('service_interval_km')->default(5000);
            $table->string('color')->nullable();
            $table->string('chassis_number')->nullable()->unique();
            $table->string('engine_number')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
