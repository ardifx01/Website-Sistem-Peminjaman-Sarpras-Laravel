<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsAndProgramsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            'TEKNIK SIPIL' => [
                'D3 Teknik Sipil',
                'Manajemen Konstruksi',
                'Teknologi Rekayasa Konstruksi Jalan & Jembatan',
                'Teknologi Rekayasa Konstruksi Bangunan Gedung',
            ],
            'TEKNIK MESIN' => [
                'Teknik Manufaktur Kapal',
                'Teknologi Rekayasa Otomotif',
                'Teknologi Rekayasa Manufaktur',
            ],
            'BISNIS & INFORMATIKA' => [
                'Bisnis Digital',
                'Teknologi Rekayasa Komputer',
                'Teknologi Rekayasa Perangkat Lunak',
            ],
            'PARIWISATA' => [
                'Destinasi Pariwisata',
                'Pengelolaan Perhotelan',
                'Manajemen Bisnis Pariwisata',
            ],
            'PERTANIAN' => [
                'Agribisnis',
                'Teknologi Produksi Ternak',
                'Teknologi Pengolahan Hasil Ternak',
                'Teknologi Produksi Tanaman Pangan',
                'Pengembangan Produk Agroindustry',
                'Teknologi Budi Daya Perikanan / Teknologi Akuakultur',
            ],
        ];

        foreach ($departments as $deptName => $programs) {
            $departmentId = DB::table('departments')->insertGetId([
                'name' => $deptName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($programs as $program) {
                DB::table('study_programs')->insert([
                    'department_id' => $departmentId,
                    'name' => $program,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}



