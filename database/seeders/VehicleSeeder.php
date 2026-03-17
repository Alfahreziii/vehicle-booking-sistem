<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Region;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $headOffice   = Region::where('type', 'head_office')->first();
        $branchOffice = Region::where('type', 'branch_office')->first();
        $mines        = Region::where('type', 'mine')->get();

        $vehicles = [
            // ── Kantor Pusat ──────────────────────────────
            [
                'plate_number'       => 'B 1234 NMC',
                'brand'              => 'Toyota',
                'model'              => 'Innova Zenix',
                'year'               => 2023,
                'type'               => 'passenger',
                'ownership'          => 'owned',
                'region_id'          => $headOffice->id,
                'status'             => 'available',
                'fuel_consumption'   => 12.5,
                'current_odometer'   => 15200,
                'service_interval_km' => 5000,
                'color'              => 'Putih',
            ],
            [
                'plate_number'       => 'B 5678 NMC',
                'brand'              => 'Toyota',
                'model'              => 'Fortuner',
                'year'               => 2022,
                'type'               => 'passenger',
                'ownership'          => 'owned',
                'region_id'          => $headOffice->id,
                'status'             => 'available',
                'fuel_consumption'   => 10.0,
                'current_odometer'   => 32000,
                'service_interval_km' => 5000,
                'color'              => 'Hitam',
            ],
            [
                'plate_number'       => 'B 9999 NMC',
                'brand'              => 'Toyota',
                'model'              => 'Hiace',
                'year'               => 2021,
                'type'               => 'passenger',
                'ownership'          => 'rented',
                'rental_company'     => 'PT Sewa Kendaraan Nusantara',
                'region_id'          => $headOffice->id,
                'status'             => 'available',
                'fuel_consumption'   => 9.0,
                'current_odometer'   => 48000,
                'service_interval_km' => 5000,
                'color'              => 'Silver',
            ],
            [
                'plate_number'       => 'B 2222 NMC',
                'brand'              => 'Mitsubishi',
                'model'              => 'L300 Box',
                'year'               => 2020,
                'type'               => 'cargo',
                'ownership'          => 'owned',
                'region_id'          => $headOffice->id,
                'status'             => 'available',
                'fuel_consumption'   => 8.5,
                'current_odometer'   => 62000,
                'service_interval_km' => 5000,
                'color'              => 'Putih',
            ],

            // ── Kantor Cabang ─────────────────────────────
            [
                'plate_number'       => 'DN 1111 NM',
                'brand'              => 'Toyota',
                'model'              => 'Land Cruiser',
                'year'               => 2022,
                'type'               => 'passenger',
                'ownership'          => 'owned',
                'region_id'          => $branchOffice->id,
                'status'             => 'available',
                'fuel_consumption'   => 8.0,
                'current_odometer'   => 28000,
                'service_interval_km' => 5000,
                'color'              => 'Putih',
            ],
            [
                'plate_number'       => 'DN 2222 NM',
                'brand'              => 'Mitsubishi',
                'model'              => 'Colt Diesel FE',
                'year'               => 2021,
                'type'               => 'cargo',
                'ownership'          => 'owned',
                'region_id'          => $branchOffice->id,
                'status'             => 'available',
                'fuel_consumption'   => 7.5,
                'current_odometer'   => 55000,
                'service_interval_km' => 5000,
                'color'              => 'Kuning',
            ],

            // ── Tambang (per lokasi, 2 kendaraan each) ────
            ...$this->mineVehicles($mines),
        ];

        foreach ($vehicles as $data) {
            Vehicle::create($data);
        }
    }

    private function mineVehicles($mines): array
    {
        $result = [];
        $platPrefixes = ['DT', 'DT', 'DT', 'DB', 'DB', 'DB'];

        foreach ($mines as $i => $mine) {
            $prefix = $platPrefixes[$i] ?? 'DD';
            $num    = ($i + 1) * 1000;

            $result[] = [
                'plate_number'        => "{$prefix} {$num} NM",
                'brand'               => 'Toyota',
                'model'               => 'Hilux Double Cabin',
                'year'                => rand(2019, 2022),
                'type'                => 'passenger',
                'ownership'           => 'owned',
                'region_id'           => $mine->id,
                'status'              => 'available',
                'fuel_consumption'    => 9.5,
                'current_odometer'    => rand(20000, 80000),
                'service_interval_km' => 5000,
                'color'               => 'Putih',
            ];

            $result[] = [
                'plate_number'        => "{$prefix} " . ($num + 1) . " NM",
                'brand'               => 'Hino',
                'model'               => 'Dutro 130 HD',
                'year'                => rand(2018, 2021),
                'type'                => 'cargo',
                'ownership'           => rand(0, 1) ? 'owned' : 'rented',
                'rental_company'      => 'PT Armada Tambang Sejahtera',
                'region_id'           => $mine->id,
                'status'              => 'available',
                'fuel_consumption'    => 6.5,
                'current_odometer'    => rand(40000, 120000),
                'service_interval_km' => 5000,
                'color'               => 'Kuning',
            ];
        }

        return $result;
    }
}
