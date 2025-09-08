<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ukms = [
            'Himpunan Mahasiswa (HIMA)',
            'UKM Pecinta Alam',
            'UKM Seni & Budaya',
            'UKM Olahraga',
            'UKM Kerohanian',
            'UKM Bahasa Inggris',
            'UKM Kewirausahaan',
            'UKM Teknologi & Riset',
        ];

        foreach ($ukms as $name) {
            DB::table('ukms')->updateOrInsert(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}



