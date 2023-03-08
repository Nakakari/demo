<?php

use App\Models\M_instansi;

function nama_instansi()
{
    $instansi = M_instansi::query()->first();
    $nama = $instansi->nama;
    return $nama;
}

function singkatan()
{
    $instansi = M_instansi::query()->first();
    $singkatan = $instansi->singkatan;
    return $singkatan;
}
function title()
{
    $instansi = M_instansi::query()->first();
    $title = $instansi->title;
    return $title;
}

function alamat_instansi()
{
    $instansi = M_instansi::query()->first();
    $alamat = $instansi->alamat;
    return $alamat;
}
function kota_instansi()
{
    $instansi = M_instansi::query()->first();
    $kota = $instansi->kota;
    return $kota;
}

function telp_instansi()
{
    $instansi = M_instansi::query()->first();
    $telp = $instansi->telp;
    return $telp;
}

function email_instansi()
{
    $instansi = M_instansi::query()->first();
    $email = $instansi->email;
    return $email;
}

function website_instansi()
{
    $instansi = M_instansi::query()->first();
    $website = $instansi->website;
    return $website;
}

function wa_instansi()
{
    $instansi = M_instansi::query()->first();
    $wa = $instansi->wa;
    return $wa;
}

function logo_instansi()
{
    $instansi = M_instansi::query()->first();
    $logo = $instansi->logo;
    return $logo;
}
function path()
{
    $instansi = M_instansi::query()->first();
    $path = $instansi->path;
    return $path;
}

function informasi_instansi()
{
    $instansi = M_instansi::query()->first();
    $informasi = $instansi->informasi;

    $hasil = explode(";", $informasi);
    return $hasil;
}
