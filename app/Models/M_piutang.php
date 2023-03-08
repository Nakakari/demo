<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_piutang extends Model
{
    use HasFactory;
    protected $table = 'tbl_invoice';

    public function listDetailPiutang($id_pelanggan)
    {
        return M_piutang::select(
            'tbl_invoice.id_invoice',
            'no_invoice',
            'nama_perusahaan',
            DB::raw("(DATE_FORMAT(jatuh_tempo,'%d %M %Y')) as jatuh_tempo"),
            'jatuh_tempo as tgl_jatuh_tempo',
            'biaya_invoice',
            'tbl_invoice.keterangan as keterangan',
            'tbl_invoice.is_lunas',
            'tbl_invoice.ppn',
            'nama_bank',
            'no_rek',
            'ttl_biaya_invoice',
            'nominal_pelunasan'
        )
            ->join('pelanggan', 'tbl_invoice.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join('bank', 'bank.id_bank', '=', 'tbl_invoice.id_bank')
            ->leftjoin('tbl_pelunasan', 'tbl_pelunasan.id_invoice', '=', 'tbl_invoice.id_invoice')
            ->where('tbl_invoice.id_pelanggan', $id_pelanggan)
            ->groupBy(
                'tbl_invoice.id_invoice',
                'no_invoice',
                'nama_perusahaan',
                'biaya_invoice',
                'tbl_invoice.keterangan',
                'jatuh_tempo',
                'tbl_invoice.is_lunas',
                'ttl_biaya_invoice',
                'tbl_invoice.ppn',
                'nama_bank',
                'no_rek',
                'nominal_pelunasan'
            )
            ->orderBy('tbl_invoice.id_invoice', "desc");
    }
}
