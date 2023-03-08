<?php

namespace App\Services;

use App\Models\M_pengiriman;
use App\Services\InvoiceService;

class InvoiceServiceImpl implements InvoiceService
{
    public function reqUpdate($form)
    {
        $update = [
            'no_invoice' => $form['no_invoice'],
            'id_bank' => $form['id_bank'],
            'jatuh_tempo' => $form['jatuh_tempo'],
            'pembuat' => $form['pembuat'],
            // 'mengetahui' => $form['mengetahui'],
            'diterbitkan' => $form['diterbitkan'],
            'ppn' => $form['ppn'],
            'keterangan' => $form['keterangan'],
        ];
        return $update;
    }
    public function nominalBiaya($id_pengiriman)
    {
        $data = M_pengiriman::whereIn('id_pengiriman', $id_pengiriman)
            ->get();

        $biaya_invoice = 0;
        foreach ($data as $role) {
            $biaya_invoice += $role->biaya;
        }
        $data = [
            'biaya_invoice' => $biaya_invoice,
        ];
        return $data;
    }

    public function hitungTotal($form, $getBiaya)
    {
        $klaim_total = 0;
        foreach ($form['klaim'] as $k => $key) {
            foreach ($form['nominal_klaim'] as $nk => $val) {
                if ($k == $nk) {
                    $klaim_total += str_ireplace(".", "", $val);
                }
            }
        }

        $bea_tambahan_total = 0;
        foreach ($form['bea_tambahan'] as $b => $bea) {
            foreach ($form['nominal_bea'] as $nb => $nom) {
                if ($b == $nb) {
                    $bea_tambahan_total += str_ireplace(".", "", $nom);
                }
            }
        }

        $ttl_biaya_invoice = $getBiaya['biaya_invoice'] + $bea_tambahan_total - $klaim_total;
        $get_ppn = $form['ppn'] / 100 * $ttl_biaya_invoice;
        $get_final_total = intval($ttl_biaya_invoice) + intval($get_ppn);
        $data = [
            'ttl_biaya_invoice' => $get_final_total,
        ];
        return $data;
    }
}
