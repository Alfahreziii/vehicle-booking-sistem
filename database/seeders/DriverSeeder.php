<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use App\Models\User;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user yang punya role driver
        $driverUsers = User::role('driver')->get();

        $licenseTypes = ['B1', 'B1', 'B2', 'B1', 'B2', 'B1', 'B2', 'B1', 'B2', 'B1'];

        foreach ($driverUsers as $i => $user) {
            Driver::create([
                'user_id'        => $user->id,
                'license_number' => 'SIM-' . strtoupper(substr($user->employee_id, -3)) . '-' . rand(100000, 999999),
                'license_type'   => $licenseTypes[$i] ?? 'B1',
                'license_expiry' => now()->addYears(rand(1, 4))->toDateString(),
                'status'         => 'available',
            ]);
        }
    }
}
