<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\instansiRequest;
use App\Models\M_instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class instansiController extends Controller
{

    public function index(M_instansi $instansi)
    {
        $instansi = $instansi->first();
        return view('Settings.instansi.v_index', compact('instansi'));
    }

    public function update(string $uuid, instansiRequest $request, M_instansi $instansi)
    {
        $form = $request->validated();
        $instansi->where('uuid', $uuid)->first();
        $form = array_merge($form, [
            'updated_by' => Auth::user()->id,
        ]);
        try {
            if(isset($form['logo'])){
                
            }
        } catch (Throwable $e){
            report($e);
            $message = 'gagal';
            $message_info = 'Gagal Terupdate';
        }
    }

}
