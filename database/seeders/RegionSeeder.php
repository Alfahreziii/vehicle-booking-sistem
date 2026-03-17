<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\Department;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'name'     => 'Kantor Pusat Jakarta',
                'type'     => 'head_office',
                'location' => 'Jakarta Selatan, DKI Jakarta',
            ],
            [
                'name'     => 'Kantor Cabang Sulawesi Tengah',
                'type'     => 'branch_office',
                'location' => 'Palu, Sulawesi Tengah',
            ],
            [
                'name'     => 'Tambang Morowali 1',
                'type'     => 'mine',
                'location' => 'Morowali, Sulawesi Tengah',
            ],
            [
                'name'     => 'Tambang Morowali 2',
                'type'     => 'mine',
                'location' => 'Morowali Utara, Sulawesi Tengah',
            ],
            [
                'name'     => 'Tambang Konawe',
                'type'     => 'mine',
                'location' => 'Konawe, Sulawesi Tenggara',
            ],
            [
                'name'     => 'Tambang Halmahera 1',
                'type'     => 'mine',
                'location' => 'Halmahera Timur, Maluku Utara',
            ],
            [
                'name'     => 'Tambang Halmahera 2',
                'type'     => 'mine',
                'location' => 'Halmahera Selatan, Maluku Utara',
            ],
            [
                'name'     => 'Tambang Sulawesi Barat',
                'type'     => 'mine',
                'location' => 'Mamuju, Sulawesi Barat',
            ],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }

        // Department untuk tiap region
        $departments = [
            'Operasional',
            'Logistik & Transportasi',
            'Human Resources',
            'Finance & Accounting',
            'HSE (Health, Safety, Environment)',
            'Engineering',
            'IT & System',
            'General Affairs',
        ];

        Region::all()->each(function ($region) use ($departments) {
            foreach ($departments as $dept) {
                Department::create([
                    'name'      => $dept,
                    'region_id' => $region->id,
                ]);
            }
        });
    }
}
