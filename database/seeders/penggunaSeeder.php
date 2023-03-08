<?php

namespace Database\Seeders;

use App\Models\M_pengguna;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class penggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        M_pengguna::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Admin',
            'peran' => 1,
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'file_foto' => 'avatar1.png',
            'tgl_lhr' => Carbon::now(),
            'alamat' => '-',
            'id_cabang' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_pengguna::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Sales',
            'peran' => 5,
            'email' => 'sales@gmail.com',
            'password' => Hash::make('12345678'),
            'file_foto' => 'avatar1.png',
            'tgl_lhr' => Carbon::now(),
            'alamat' => '-',
            'id_cabang' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_pengguna::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Checker',
            'peran' => 7,
            'email' => 'checker@gmail.com',
            'password' => Hash::make('12345678'),
            'file_foto' => 'avatar1.png',
            'tgl_lhr' => Carbon::now(),
            'alamat' => '-',
            'id_cabang' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
