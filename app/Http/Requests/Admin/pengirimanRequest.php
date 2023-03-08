<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class pengirimanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_cabang_tujuan'  => 'required',
            'dari_cabang'  => 'required',
            'tgl_masuk'  => 'required',
            'nama_pengirim' => 'required',
            'alamat_pengirim' => 'required',
            'tlp_pengirim' => 'required',
            'status_sent'  => 'required',
            'kota_pengirim'  => 'required',
            'kota_penerima'  => 'required',
            'nama_penerima'  => 'required',
            'alamat_penerima'  => 'required',
            'no_penerima'  => 'required',
            'isi_barang'  => 'required',
            'koli'  => 'required',
            'no_pelayanan'  => 'required',
            'no_moda'  => 'required',
            'ttl_biaya'  => 'required',
            'keterangan'  => 'required',
            'bea'  => 'required',
            'bea_penerus'  => 'required',
            'bea_packing'  => 'required',
            'asuransi'  => 'required',
            'tipe_pembayaran'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_cabang_tujuan.required'  => 'Mohon diisi!',
            'dari_cabang.required'  => 'Mohon diisi!',
            'tgl_masuk.required'  => 'Mohon diisi!',
            'nama_pengirim.required' => 'Mohon diisi!',
            'alamat_pengirim.required' => 'Mohon diisi!',
            'tlp_pengirim.required' => 'Mohon diisi!',
            'status_sent.required'  => 'Mohon diisi!',
            'kota_pengirim.required'  => 'Mohon diisi!',
            'kota_penerima.required'  => 'Mohon diisi!',
            'nama_penerima.required'  => 'Mohon diisi!',
            'alamat_penerima.required'  => 'Mohon diisi!',
            'no_penerima.required'  => 'Mohon diisi!',
            'isi_barang.required'  => 'Mohon diisi!',
            'koli.required'  => 'Mohon diisi!',
            'no_pelayanan.required'  => 'Mohon diisi!',
            'no_moda.required'  => 'Mohon diisi!',
            'ttl_biaya.required'  => 'Mohon diisi!',
            'keterangan.required'  => 'Mohon diisi!',
            'bea.required'  => 'Mohon diisi!',
            'bea_penerus.required'  => 'Mohon diisi!',
            'bea_packing.required'  => 'Mohon diisi!',
            'asuransi.required'  => 'Mohon diisi!',
            'tipe_pembayaran.required'  => 'Mohon diisi!'
        ];
    }
}
