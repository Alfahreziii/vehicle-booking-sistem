<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definisi permissions
        $permissions = [
            // Booking
            'booking.create',
            'booking.view',
            'booking.edit',
            'booking.delete',
            // Approval
            'approval.view',
            'approval.process',
            // Vehicle
            'vehicle.create',
            'vehicle.view',
            'vehicle.edit',
            'vehicle.delete',
            // Driver
            'driver.create',
            'driver.view',
            'driver.edit',
            'driver.delete',
            // Report
            'report.view',
            'report.export',
            // User management
            'user.create',
            'user.view',
            'user.edit',
            'user.delete',
            // Dashboard
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role: Admin — akses penuh
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Role: Approver — hanya approval & lihat
        $approver = Role::firstOrCreate(['name' => 'approver']);
        $approver->givePermissionTo([
            'approval.view',
            'approval.process',
            'booking.view',
            'dashboard.view',
            'report.view',
        ]);

        // Role: Driver — lihat jadwal saja
        $driver = Role::firstOrCreate(['name' => 'driver']);
        $driver->givePermissionTo(['dashboard.view', 'booking.view']);

        // Role: Viewer — laporan saja
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->givePermissionTo(['dashboard.view', 'report.view']);
    }
}
