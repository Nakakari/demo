<?php

namespace App\Actions;

use App\Models\M_beaTambahanInvoice;
use App\Models\M_detailInvoice;
use App\Models\M_klaimInvoice;
use Illuminate\Support\Facades\DB;

class InvoiceAction
{
    public function addDetailInvoice($id_pengiriman, $id_invoice)
    {
        DB::transaction(
            function () use ($id_pengiriman, $id_invoice): void {
                foreach ($id_pengiriman as $peng) {
                    M_detailInvoice::create([
                        'id_invoice' => $id_invoice,
                        'id_pengiriman' => $peng
                    ]);
                }
            }
        );
    }

    public function addKlaim($form, $id_invoice)
    {
        DB::transaction(
            function () use ($form, $id_invoice): void {
                foreach ($form['klaim'] as $k => $key) {
                    foreach ($form['nominal_klaim'] as $nk => $val) {
                        if ($k == $nk) {
                            M_klaimInvoice::create([
                                'id_invoice' => $id_invoice,
                                'klaim' => $key,
                                'nominal_klaim' => str_ireplace(".", "", $val),
                            ]);
                        }
                    }
                }
            }
        );
    }

    public function addBea($form, $id_invoice)
    {
        DB::transaction(
            function () use ($form, $id_invoice): void {
                foreach ($form['bea_tambahan'] as $b => $bea) {
                    foreach ($form['nominal_bea'] as $nb => $nom) {
                        if ($b == $nb) {
                            M_beaTambahanInvoice::create([
                                'id_invoice' => $id_invoice,
                                'bea_tambahan' => $bea,
                                'nominal_bea' => str_ireplace(".", "", $nom),
                            ]);
                        }
                    }
                }
            }
        );
    }

    public function updateKlaim($form, $id_invoice)
    {
        DB::transaction(
            function () use ($form, $id_invoice): void {
                M_klaimInvoice::whereIn('id_invoice', [$id_invoice])->delete();
                foreach ($form['klaim'] as $k => $key) {
                    foreach ($form['nominal_klaim'] as $nk => $val) {
                        if ($k == $nk) {
                            M_klaimInvoice::create([
                                'id_invoice' => $id_invoice,
                                'klaim' => $key,
                                'nominal_klaim' => str_ireplace(".", "", $val),
                            ]);
                        }
                    }
                }
            }
        );
    }

    public function updateBea($form, $id_invoice)
    {
        DB::transaction(
            function () use ($form, $id_invoice): void {
                M_beaTambahanInvoice::whereIn('id_invoice', [$id_invoice])->delete();
                foreach ($form['bea_tambahan'] as $b => $bea) {
                    foreach ($form['nominal_bea'] as $nb => $nom) {
                        if ($b == $nb) {
                            M_beaTambahanInvoice::create([
                                'id_invoice' => $id_invoice,
                                'bea_tambahan' => $bea,
                                'nominal_bea' => str_ireplace(".", "", $nom),
                            ]);
                        }
                    }
                }
            }
        );
    }
    public function updateDetailInvoice($id_pengiriman, $id_invoice)
    {
        DB::transaction(
            function () use ($id_pengiriman, $id_invoice): void {
                M_detailInvoice::whereIn('id_invoice', [$id_invoice])->delete();
                foreach ($id_pengiriman as $peng) {
                    M_detailInvoice::create([
                        'id_invoice' => $id_invoice,
                        'id_pengiriman' => $peng
                    ]);
                }
            }
        );
    }
}
