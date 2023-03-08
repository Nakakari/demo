<?php

namespace App\Providers;

use App\Models\M_pelunasan;
use App\Observers\riwayatPembayaranObserver;
use App\Services\InvoiceService;
use App\Services\InvoiceServiceImpl;
use App\Services\PiutangService;
use App\Services\PiutangServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InvoiceService::class, InvoiceServiceImpl::class);
        $this->app->bind(PiutangService::class, PiutangServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');
        M_pelunasan::observe(riwayatPembayaranObserver::class);
    }
}
