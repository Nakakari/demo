<?php

namespace App\Services;


interface InvoiceService
{
    public function reqUpdate($form);
    public function nominalBiaya($id_pengiriman);
    public function hitungTotal($form, $getBiaya);
}
