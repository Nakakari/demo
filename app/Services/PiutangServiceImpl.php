<?php

namespace App\Services;

use App\Models\M_pelunasan;
use App\Models\M_riwayatPembayaran;
use App\Services\PiutangService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PiutangServiceImpl implements PiutangService
{
    public function inputPiutang($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya)
    {
        DB::transaction(function () use ($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya): void {
            M_pelunasan::create([
                'id_invoice'  => $id_invoice,
                'nominal_pelunasan'  => $nominal_pelunasan,
            ]);
            if ($nominal_pelunasan == $get_biaya) {
                $is_lunas = [
                    'is_lunas' => 1
                ];
            } else if ($nominal_pelunasan < $get_biaya) {
                $is_lunas = [
                    'is_lunas' => 0
                ];
            }
            DB::table('pengiriman')->whereIn('id_pengiriman', $id_pengiriman)
                ->update($is_lunas);
            DB::table('tbl_invoice')->where('id_invoice', $id_invoice)->update($is_lunas);
        });

        return true;
    }
    public function updatePiutang($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya, $update_peluansan)
    {
        DB::transaction(function () use ($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya, $update_peluansan): void {
            M_pelunasan::where('id_invoice', $id_invoice)->update([
                'nominal_pelunasan' => $update_peluansan
            ]);
            M_riwayatPembayaran::create([
                'id_invoice'  => $id_invoice,
                'pembayaran'  => $nominal_pelunasan,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            if ($update_peluansan == $get_biaya) {
                $is_lunas = [
                    'is_lunas' => 1
                ];
            } else if ($update_peluansan < $get_biaya) {
                $is_lunas = [
                    'is_lunas' => 0
                ];
            }
            DB::table('pengiriman')->whereIn('id_pengiriman', $id_pengiriman)
                ->update($is_lunas);
            DB::table('tbl_invoice')->where('id_invoice', $id_invoice)->update($is_lunas);
        });
        return true;
    }
}
