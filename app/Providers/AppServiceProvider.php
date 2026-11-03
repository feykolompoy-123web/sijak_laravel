<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tampilan pagination mengikuti desain SIJAK (resources/views/vendor/pagination/sijak.blade.php).
        Paginator::defaultView('vendor.pagination.sijak');
        Paginator::defaultSimpleView('vendor.pagination.sijak');
    }
}
