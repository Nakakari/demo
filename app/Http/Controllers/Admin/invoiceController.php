<?php

namespace App\Http\Controllers\Admin;

use App\Actions\InvoiceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\editInvoiceRequest;
use App\Http\Requests\invoiceRequest;
use App\Models\M_akunBank;
use App\Models\M_cabang;
use App\Models\M_detailInvoice;
use App\Models\M_invoice;
use Illuminate\Http\Request;
use App\Models\M_jabatan;
use App\Models\M_pelanggan;
use App\Models\M_pengiriman;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use PDF;

class invoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
        ];
        return view('Admin.Invoice.v_invoice', $data);
    }

    public function list_pelanggan(Request $request)
    {
        $columns = [
            'nama_perusahaan',
            'kota',
            'alamat',
            'nama_spv',
            'tlp_spv',
            'k_perusahaan',
            'email_prshn'
        ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = M_pelanggan::select(
            'pelanggan.nama_perusahaan',
            DB::raw('COUNT(IF((pengiriman.is_buat = 0) AND (pengiriman.tipe_pembayaran = 1), 1, NULL)) as inv_blm_buat'),
            DB::raw('COUNT(IF(pengiriman.is_lunas = 0, 1, NULL)) as inv_blm_lunas'),
            DB::raw('COUNT(pengiriman.no_resi)'),
            'pelanggan.id_pelanggan'
        )
            ->leftjoin('pengiriman', 'pengiriman.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            // ->where('pengiriman.tipe_pembayaran', '=', 1)
            ->groupBy('pelanggan.nama_perusahaan', 'pelanggan.id_pelanggan')
            ->orderBy('pengiriman.id_pelanggan', "desc");
        // DB::raw('COUNT(IF(pengiriman.is_buat = 0, IF(pengiriman.tipe_pembayaran = 1, 1, 1), 1)) as inv_blm_buat')
        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pelanggan.nama_perusahaan) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pelanggan.kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function transPelanggan($id_pelanggan)
    {
        $id = base64_decode($id_pelanggan);
        $data = [
            'kondisi' => M_cabang::getKondisi(),
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
            'getPerusahaan' => M_pelanggan::getNama($id),
            'id_pelanggan' => $id
        ];
        return view('Admin.Invoice.v_showPenjualanPelanggan', $data);
    }

    public function invPelanggan(Request $request, $id_pelanggan)
    {
        $id_pelanggan = base64_decode($id_pelanggan);
        $columns = [
            'id_pengiriman', 'no_resi', 'nama_penerima',
            'biaya', 'tipe_pembayaran',
            'nama_pengirim',  'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_pengiriman())->invPelanggan($id_pelanggan);

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.no_resi_manual) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.kota_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null || request()->input('sampai') != null) {
            $data = $data->whereBetween('pengiriman.tgl_masuk', [request()->dari, request()->sampai]);
        }

        if (request()->input('kondisi') != null) {
            $data = $data->where('pengiriman.is_kondisi_resi', request()->kondisi);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function reselect($id_invoice, M_invoice $invoice, M_detailInvoice $detail_invoice)
    {
        $id_invoice = base64_decode($id_invoice);
        $invoice = $invoice->where('id_invoice', $id_invoice)->first();
        $id_pelanggan = $invoice["id_pelanggan"];
        $detail_invoice = $detail_invoice->where('id_invoice', $id_invoice)->get();
        $arr = [];
        foreach ($detail_invoice as $v) {
            $arr[] = $v['id_pengiriman'];
        }
        $data = [
            'kondisi' => M_cabang::getKondisi(),
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
            'getPerusahaan' => M_pelanggan::getNama($id_pelanggan),
            'id_pelanggan' => $id_pelanggan,
            'selected' => json_encode($arr),
            'id_invoice' => $id_invoice,
        ];
        return view('Admin.Invoice.v_reselectInvoice', $data);
    }

    public function invoice($id_pelanggan)
    {
        $data = [
            'id_pelanggan' => $id_pelanggan,
            'jab' => M_jabatan::getJab(),
            'getPerusahaan' => M_pelanggan::getNama(base64_decode($id_pelanggan)),
            'bank' => M_akunBank::getAll()
        ];
        return view('Admin.Invoice.v_buatInvoice', $data);
    }

    public function push_invoice(invoiceRequest $request, $id_pelanggan, InvoiceService $invService)
    {
        $form = $request->validated();

        $getid = $form['id_pengiriman'];
        $id = base64_decode($id_pelanggan);
        $id_pengiriman = explode(',', $getid);

        $getBiaya = $invService->nominalBiaya($id_pengiriman);
        $hitungTotal = $invService->hitungTotal($form, $getBiaya);

        $form = array_merge($form, $getBiaya, $hitungTotal, [
            'id_pelanggan' => $id,
            'is_lunas' => 0,
            'created_by' => Auth::user()->id,
        ]);

        try {
            DB::transaction(function () use ($form, $id_pengiriman) {
                $invoice = M_invoice::create($form);
                // dd($invoice);

                M_pengiriman::whereIn('id_pengiriman', $id_pengiriman)
                    ->update(['is_buat' => 1, 'is_lunas' => 0]);

                (new InvoiceAction)->addDetailInvoice($id_pengiriman, $invoice->id_invoice);
                (new InvoiceAction)->addKlaim($form, $invoice->id_invoice);
                (new InvoiceAction)->addBea($form, $invoice->id_invoice);
            });
            $message = "pesan";
            $info = "Data Berhasil Ditambah!!";
        } catch (Throwable $e) {
            report($e);
            $message = "gagal";
            $info = $e;
        }
        return redirect('/piutang')->with($message, $info);
    }

    public function edit_invoice($id_invoice, M_detailInvoice $detail_invoice)
    {
        $id_invoice = base64_decode($id_invoice);
        $invoice = M_invoice::where('id_invoice', $id_invoice)->first();
        $id_pelanggan = $invoice['id_pelanggan'];
        $detail_invoice = $detail_invoice->where('id_invoice', $id_invoice)->get();
        $arr = [];
        foreach ($detail_invoice as $v) {
            $arr[] = $v['id_pengiriman'];
        }
        $data = [
            'id_pelanggan' => $id_pelanggan,
            'jab' => M_jabatan::getJab(),
            'getPerusahaan' => M_pelanggan::getNama($id_pelanggan),
            'bank' => M_akunBank::getAll(),
            'id_invoice' => $id_invoice,
            'detailInvoiceList' => M_detailInvoice::where('id_invoice', $id_invoice)->get(),
            'invoice' => $invoice,
            'detailInvoice' => $arr,
        ];
        return view('Admin.Invoice.v_edit', $data);
    }

    public function reselectInvPelanggan(Request $request, $id_pelanggan)
    {
        $id_pelanggan = base64_decode($id_pelanggan);
        $columns = [
            'id_pengiriman', 'no_resi', 'nama_penerima',
            'biaya', 'tipe_pembayaran',
            'nama_pengirim',  'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_pengiriman())->resinvPelanggan($id_pelanggan);

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.no_resi_manual) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.kota_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null || request()->input('sampai') != null) {
            $data = $data->whereBetween('pengiriman.tgl_masuk', [request()->dari, request()->sampai]);
        }

        if (request()->input('kondisi') != null) {
            $data = $data->where('pengiriman.is_kondisi_resi', request()->kondisi);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function update_invoice(editInvoiceRequest $request, $id_invoice, InvoiceService $invService)
    {
        $form = $request->validated();
        $id_pengiriman = array_map('strval', explode(',', $form['id_pengiriman']));
        $getBiaya = $invService->nominalBiaya($id_pengiriman);
        $hitungTotal = $invService->hitungTotal($form, $getBiaya);
        $form = array_merge($form, $getBiaya, $hitungTotal);
        $update = $invService->reqUpdate($form);
        // dd($form);

        try {
            DB::transaction(function () use ($form, $update, $id_invoice, $getBiaya, $hitungTotal, $id_pengiriman) {
                $update = array_merge($update, $getBiaya, $hitungTotal, [
                    'updated_by' => Auth::user()->id,
                ]);
                // dd($update);
                $invoice = M_invoice::where('id_invoice', $id_invoice)->first();
                $invoice->update($update);

                (new InvoiceAction)->updateDetailInvoice($id_pengiriman, $id_invoice);
                (new InvoiceAction)->updateKlaim($form, $id_invoice);
                (new InvoiceAction)->updateBea($form, $id_invoice);
            });
            $message = "pesan";
            $info = "Data Berhasil Ditambah!!";
        } catch (Throwable $e) {
            report($e);
            $message = "gagal";
            $info = $e;
        }
        return redirect()->route('detail_piutang', $form['id_pelanggan'])->with($message, $info);
    }

    public function printInvoice(string $id_invoice, M_invoice $invoice)
    {
        $id_invoice = base64_decode($id_invoice);
        $invoice = $invoice->where('id_invoice', $id_invoice)->first();
        $data = [
            'invoice' => $invoice,
        ];
        $pdf = PDF::loadView('Admin.Piutang.v_cetak', $data)->setPaper('a4', 'portrait');
        return $pdf->stream('Cetak Invoice ' . $invoice->pelanggan->nama_perusahaan . '.pdf');
    }
}
