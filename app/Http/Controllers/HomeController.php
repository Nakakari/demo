<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M_cabang;
use App\Models\M_jabatan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'cab' => M_cabang::getAll(),
            'jab' => M_jabatan::getJab(),
        ];
        return view('home', $data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome()
    {
        $data = [
            'cab' => M_cabang::getAll(),
            'jab' => M_jabatan::getJab(),
        ];
        return view('adminHome', $data);
    }

    public function salesHome()
    {
        $data = [
            'cab' => M_cabang::getAll(),
            'jab' => M_jabatan::getJab(),
        ];
        return view('salesHome', $data);
    }

    public function checkerHome()
    {
        $data = [
            'cab' => M_cabang::getAll(),
            'jab' => M_jabatan::getJab(),
        ];
        return view('checkerHome', $data);
    }
}
