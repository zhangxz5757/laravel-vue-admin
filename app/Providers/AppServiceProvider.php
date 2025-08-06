<?php

namespace App\Providers;

use App\Http\Middleware\Pagination;
use Illuminate\Pagination\Paginator;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 修改默认分页
        Paginator::useBootstrap();
        Paginator::$defaultSimpleView = 'pagination::bootstrap-4';

        app()->bind('Illuminate\Pagination\LengthAwarePaginator', function ($app, $options) {
            return (new Pagination($options['items'], $options['total'], $options['perPage'], $options['currentPage'], $options['options']));
        });
    }
}
