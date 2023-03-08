<?php

namespace Database\Factories;

use App\Models\M_cabang;
use App\Models\M_member_sales;
use App\Models\M_pelanggan;
use App\Models\M_pelayanan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class M_pengirimanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $cab = M_cabang::all()->random();
        $pelanggan = M_pelanggan::all()->random();
        $pelayanan = M_pelayanan::all()->random();

        $year = $this->faker->year();
        $month = $this->faker->month();
        $thnBln = $year . $month;
        $kode = strtoupper($cab->kode_area) . '-' . $thnBln . $this->faker->randomDigit(4);

        $bea = $this->faker->numberBetween(500000, 10000000);
        $bea_penerus = $this->faker->numberBetween(500000, 10000000);
        $bea_packing = $this->faker->numberBetween(500000, 1000000);
        $asuransi = $this->faker->numberBetween(500000, 1000000);
        $biaya = $bea + $bea_penerus + $bea_packing + $asuransi;
        return [
            'dari_cabang' => $this->faker->numberBetween(1, 3),
            'no_resi' => $kode,
            'nama_pengirim' => $this->faker->name(),
            'kota_pengirim' => $this->faker->city(),
            'alamat_pengirim' => $this->faker->address(),
            'tlp_pengirim' => $this->faker->phoneNumber(),
            'id_cabang_tujuan' => $cab->id_cabang,
            'status_sent' => $this->faker->numberBetween(1, 8),
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'nama_penerima' => $this->faker->name(),
            'no_penerima' => $this->faker->phoneNumber(),
            'kota_penerima' => $this->faker->city(),
            'alamat_penerima' => $this->faker->address(),
            'isi_barang' => $this->faker->text(10),
            'berat' => $this->faker->numberBetween(5, 1000),
            'koli' => $this->faker->numberBetween(5, 1000),
            'no_pelayanan' => $pelayanan->id_pelayanan,
            'no_moda' => $this->faker->title(),
            'bea' => $bea,
            'bea_penerus' => $bea_penerus,
            'bea_packing' => $bea_packing,
            'asuransi' => $asuransi,
            'biaya' => $biaya,
            'tipe_pembayaran' => $this->faker->numberBetween(1, 3),
            'tgl_masuk' => $this->faker->date(),
            'is_lunas' => 0,
            'is_kondisi_resi' => 1,
            'id_member_sales' => $this->faker->numberBetween(1, 10),
            'created_by' => $this->faker->numberBetween(1, 2),
            'tgl_tiba' => $this->faker->date(),
            'file_bukti' => '1671587008_avatar1.png'
        ];
    }
}
