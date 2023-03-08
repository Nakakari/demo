<?php

namespace App\Exports;

use App\Models\M_pengiriman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PenjualanExport implements FromView
{
    protected $id_cabang;
    protected $dari_tanggal;
    protected $sampai_tanggal;
    protected $filter_kondisi;

    public function __construct($id_cabang, $dari_tanggal, $sampai_tanggal, $filter_kondisi)
    {
        $this->id_cabang = $id_cabang;
        $this->dari_tanggal = $dari_tanggal;
        $this->sampai_tanggal = $sampai_tanggal;
        $this->filter_kondisi = $filter_kondisi;
    }

    public function view(): View
    {
        $data = (new M_pengiriman())->listPenjualan();
        if ($this->dari_tanggal != null || $this->sampai_tanggal) {
            $data =  $data->whereBetween('pengiriman.tgl_masuk', [$this->dari_tanggal, $this->sampai_tanggal]);
        }

        if ($this->filter_kondisi != null) {
            $data =  $data->where('pengiriman.is_kondisi_resi', $this->filter_kondisi);
        }

        if ($this->id_cabang != null) {
            $data =  $data->where('pengiriman.dari_cabang', $this->id_cabang);
        }

        $data =  $data->get();
        return view('Admin.Penjualan.v_excell', compact('data'));
        // dd($data);
    }
}
