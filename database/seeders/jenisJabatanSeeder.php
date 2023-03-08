<?php

namespace Database\Seeders;

use App\Models\M_jabatan;
use Illuminate\Database\Seeder;

class jenisJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        M_jabatan::create([
            'nama_jabatan' => 'Admin',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_jabatan::create([
            'nama_jabatan' => 'Direktur',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_jabatan::create([
            'nama_jabatan' => 'Manager Oprasional',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_jabatan::create([
            'nama_jabatan' => 'Kepala Cabang',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_jabatan::create([
            'nama_jabatan' => 'Sales Counter',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_jabatan::create([
            'nama_jabatan' => 'Agen',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_jabatan::create([
            'nama_jabatan' => 'Checker',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
