<?php

use App\Http\Controllers\Settings\instansiController;
use Illuminate\Support\Facades\Route;

Route::prefix('instansi')->group(function(){
    Route::controller(instansiController::class)->group(function(){
        Route::get('', 'index')->name('instansi');
        Route::put('update/{uuid}', 'update')->name('instansi.update');
    });
});
