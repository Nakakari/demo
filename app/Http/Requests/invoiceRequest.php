<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class invoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'no_invoice' => ['required', 'max:255', 'unique:tbl_invoice'],
            'id_pengiriman' => 'required',
            'id_bank' => 'required',
            'jatuh_tempo' => 'required',
            'pembuat' => 'required',
            // 'mengetahui' => 'required',
            'diterbitkan' => 'required',
            'ppn' => 'required',
            'keterangan' => 'required',
            'klaim' => ['required'],
            'nominal_klaim' => ['required'],
            'bea_tambahan' => ['required'],
            'nominal_bea' => ['required'],
        ];
    }
}
