<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Region;
use App\Models\Department;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $headOffice   = Region::where('type', 'head_office')->first();
        $branchOffice = Region::where('type', 'branch_office')->first();
        $mines        = Region::where('type', 'mine')->get();

        $logisticDept = Department::where('name', 'Logistik & Transportasi')
            ->where('region_id', $headOffice->id)->first();
        $opsDept      = Department::where('name', 'Operasional')
            ->where('region_id', $headOffice->id)->first();

        // ── Super Admin ───────────────────────────────────
        $superAdmin = User::create([
            'name'          => 'Super Administrator',
            'email'         => 'superadmin@nikelmining.co.id',
            'password'      => Hash::make('password'),
            'employee_id'   => 'EMP-0001',
            'phone'         => '081200000001',
            'region_id'     => $headOffice->id,
            'department_id' => $logisticDept->id,
        ]);
        $superAdmin->assignRole('admin');

        // ── Admin Pool Kendaraan (Kantor Pusat) ───────────
        $adminPool = User::create([
            'name'          => 'Budi Santoso',
            'email'         => 'admin.pool@nikelmining.co.id',
            'password'      => Hash::make('password'),
            'employee_id'   => 'EMP-0002',
            'phone'         => '081200000002',
            'region_id'     => $headOffice->id,
            'department_id' => $logisticDept->id,
        ]);
        $adminPool->assignRole('admin');

        // ── Admin Pool Cabang ─────────────────────────────
        $adminCabang = User::create([
            'name'          => 'Dewi Rahayu',
            'email'         => 'admin.cabang@nikelmining.co.id',
            'password'      => Hash::make('password'),
            'employee_id'   => 'EMP-0003',
            'phone'         => '081200000003',
            'region_id'     => $branchOffice->id,
            'department_id' => Department::where('name', 'Logistik & Transportasi')
                ->where('region_id', $branchOffice->id)->first()?->id,
        ]);
        $adminCabang->assignRole('admin');

        // ── Approver Level 1 — Kepala Bagian ─────────────
        $approver1 = User::create([
            'name'          => 'Hendra Wijaya',
            'email'         => 'kabag.ops@nikelmining.co.id',
            'password'      => Hash::make('password'),
            'employee_id'   => 'EMP-0010',
            'phone'         => '081200000010',
            'region_id'     => $headOffice->id,
            'department_id' => $opsDept->id,
        ]);
        $approver1->assignRole('approver');

        // ── Approver Level 2 — Manager ────────────────────
        $approver2 = User::create([
            'name'          => 'Siti Nurhaliza',
            'email'         => 'manager.ops@nikelmining.co.id',
            'password'      => Hash::make('password'),
            'employee_id'   => 'EMP-0011',
            'phone'         => '081200000011',
            'region_id'     => $headOffice->id,
            'department_id' => $opsDept->id,
        ]);
        $approver2->assignRole('approver');

        // ── Approver Tambang (per lokasi) ─────────────────
        $mineApprovers = [
            ['name' => 'Agus Prayitno',    'email' => 'kabag.morowali1@nikelmining.co.id', 'emp' => 'EMP-0012'],
            ['name' => 'Rini Oktaviani',   'email' => 'kabag.morowali2@nikelmining.co.id', 'emp' => 'EMP-0013'],
            ['name' => 'Joko Widodo',      'email' => 'kabag.konawe@nikelmining.co.id',    'emp' => 'EMP-0014'],
            ['name' => 'Fitri Handayani',  'email' => 'kabag.halmahera1@nikelmining.co.id', 'emp' => 'EMP-0015'],
            ['name' => 'Bambang Susanto',  'email' => 'kabag.halmahera2@nikelmining.co.id', 'emp' => 'EMP-0016'],
            ['name' => 'Yuni Astuti',      'email' => 'kabag.sulbar@nikelmining.co.id',    'emp' => 'EMP-0017'],
        ];

        foreach ($mineApprovers as $i => $data) {
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'password'      => Hash::make('password'),
                'employee_id'   => $data['emp'],
                'phone'         => '08120000' . str_pad($i + 12, 4, '0', STR_PAD_LEFT),
                'region_id'     => $mines[$i]->id ?? $mines->last()->id,
                'department_id' => Department::where('name', 'Operasional')
                    ->where('region_id', $mines[$i]->id ?? $mines->last()->id)
                    ->first()?->id,
            ]);
            $user->assignRole('approver');
        }

        // ── Driver (10 orang) — dibuat dulu sebagai User ──
        // Driver diisi di DriverSeeder, di sini cukup buat User-nya
        $driverData = [
            ['name' => 'Wahyu Setiawan',   'email' => 'driver1@nikelmining.co.id',  'emp' => 'DRV-001', 'region_id' => $headOffice->id],
            ['name' => 'Rizky Pratama',    'email' => 'driver2@nikelmining.co.id',  'emp' => 'DRV-002', 'region_id' => $headOffice->id],
            ['name' => 'Eko Saputra',      'email' => 'driver3@nikelmining.co.id',  'emp' => 'DRV-003', 'region_id' => $branchOffice->id],
            ['name' => 'Dimas Kurniawan',  'email' => 'driver4@nikelmining.co.id',  'emp' => 'DRV-004', 'region_id' => $branchOffice->id],
            ['name' => 'Fajar Nugroho',    'email' => 'driver5@nikelmining.co.id',  'emp' => 'DRV-005', 'region_id' => $mines[0]->id],
            ['name' => 'Hadi Subroto',     'email' => 'driver6@nikelmining.co.id',  'emp' => 'DRV-006', 'region_id' => $mines[0]->id],
            ['name' => 'Irwan Kusuma',     'email' => 'driver7@nikelmining.co.id',  'emp' => 'DRV-007', 'region_id' => $mines[1]->id],
            ['name' => 'Lutfi Hakim',      'email' => 'driver8@nikelmining.co.id',  'emp' => 'DRV-008', 'region_id' => $mines[2]->id],
            ['name' => 'Muhamad Ridwan',   'email' => 'driver9@nikelmining.co.id',  'emp' => 'DRV-009', 'region_id' => $mines[3]->id],
            ['name' => 'Nanang Hidayat',   'email' => 'driver10@nikelmining.co.id', 'emp' => 'DRV-010', 'region_id' => $mines[4]->id],
        ];

        foreach ($driverData as $data) {
            $deptId = Department::where('name', 'Logistik & Transportasi')
                ->where('region_id', $data['region_id'])->first()?->id;
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'password'      => Hash::make('password'),
                'employee_id'   => $data['emp'],
                'phone'         => '0812' . rand(10000000, 99999999),
                'region_id'     => $data['region_id'],
                'department_id' => $deptId,
            ]);
            $user->assignRole('driver');
        }

        // ── Pegawai biasa (pemohon) ───────────────────────
        $employees = [
            ['name' => 'Ahmad Fauzi',    'email' => 'ahmad.fauzi@nikelmining.co.id',    'region_id' => $headOffice->id],
            ['name' => 'Bagas Wicaksono', 'email' => 'bagas.w@nikelmining.co.id',         'region_id' => $headOffice->id],
            ['name' => 'Citra Lestari',  'email' => 'citra.l@nikelmining.co.id',         'region_id' => $branchOffice->id],
            ['name' => 'Doni Setiawan',  'email' => 'doni.s@nikelmining.co.id',          'region_id' => $mines[0]->id],
            ['name' => 'Eka Putri',      'email' => 'eka.p@nikelmining.co.id',           'region_id' => $mines[1]->id],
        ];

        foreach ($employees as $i => $data) {
            $deptId = Department::where('name', 'Operasional')
                ->where('region_id', $data['region_id'])->first()?->id;
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'password'      => Hash::make('password'),
                'employee_id'   => 'EMP-' . str_pad(100 + $i, 4, '0', STR_PAD_LEFT),
                'phone'         => '0813' . rand(10000000, 99999999),
                'region_id'     => $data['region_id'],
                'department_id' => $deptId,
            ]);
            $user->assignRole('viewer');
        }
    }
}
