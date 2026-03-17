<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,  // 1. Role & permission dulu
            RegionSeeder::class,          // 2. Region & department
            UserSeeder::class,            // 3. User (butuh region & role)
            VehicleSeeder::class,         // 4. Kendaraan (butuh region)
            DriverSeeder::class,          // 5. Driver (butuh user)
            BookingSeeder::class,         // 6. Booking contoh (butuh semua)
        ]);
    }
}
