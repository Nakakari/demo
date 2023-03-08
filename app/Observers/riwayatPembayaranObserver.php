<?php

namespace App\Observers;

use App\Models\M_pelunasan;
use App\Models\M_riwayatPembayaran;
use Illuminate\Support\Facades\Auth;

class riwayatPembayaranObserver
{
    /**
     * Handle the M_pelunasan "created" event.
     *
     * @param  \App\Models\M_pelunasan  $m_pelunasan
     * @return void
     */
    public function created(M_pelunasan $m_pelunasan)
    {
        M_riwayatPembayaran::create([
            'id_invoice' => $m_pelunasan->id_invoice,
            'pembayaran' => $m_pelunasan->nominal_pelunasan,
            'created_by' => Auth::user()->id,
        ]);
    }

    /**
     * Handle the M_pelunasan "updated" event.
     *
     * @param  \App\Models\M_pelunasan  $m_pelunasan
     * @return void
     */
    public function updated(M_pelunasan $m_pelunasan)
    {
        M_riwayatPembayaran::create([
            'id_invoice' => $m_pelunasan->id_invoice,
            'pembayaran' => $m_pelunasan->nominal_pelunasan,
            'created_by' => Auth::user()->id,
        ]);
    }

    /**
     * Handle the M_pelunasan "deleted" event.
     *
     * @param  \App\Models\M_pelunasan  $m_pelunasan
     * @return void
     */
    public function deleted(M_pelunasan $m_pelunasan)
    {
        //
    }

    /**
     * Handle the M_pelunasan "restored" event.
     *
     * @param  \App\Models\M_pelunasan  $m_pelunasan
     * @return void
     */
    public function restored(M_pelunasan $m_pelunasan)
    {
        //
    }

    /**
     * Handle the M_pelunasan "force deleted" event.
     *
     * @param  \App\Models\M_pelunasan  $m_pelunasan
     * @return void
     */
    public function forceDeleted(M_pelunasan $m_pelunasan)
    {
        //
    }
}
