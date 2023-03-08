<?php

namespace App\Services;


interface PiutangService
{
    public function inputPiutang($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya);
    public function updatePiutang($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya, $update_peluansan);
}
