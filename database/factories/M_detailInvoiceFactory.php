<?php

namespace Database\Factories;

use App\Models\M_invoice;
use App\Models\M_pelanggan;
use App\Models\M_pengiriman;
use Illuminate\Database\Eloquent\Factories\Factory;

class M_detailInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $invoice = M_invoice::all()->random();
        $pelanggan = M_pelanggan::all()->random();
        $pengiriman = M_pengiriman::all()->random();
        return [
            'id_invoice' => $invoice->id_invoice,
            'id_pengiriman' => $pengiriman->id_pengiriman,
            'id_pelanggan' => $pelanggan->id_pelanggan
        ];
    }
}
