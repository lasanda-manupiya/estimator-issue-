<?php

namespace Modules\PurchaseManager\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\PurchaseManager\Entities\Purchase;
use Modules\PurchaseManager\Entities\PurchaseOrder;
use Modules\PurchaseManager\Entities\PurchaseInvoice;
use Modules\PurchaseManager\Observers\PurchaseObserver;
use Modules\PurchaseManager\Observers\PurchaseOrderObserver;
use Modules\PurchaseManager\Observers\PurchaseInvoiceObserver;

class PurchaseManagerServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        // register observers
        Purchase::observe(PurchaseObserver::class);
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        PurchaseInvoice::observe(PurchaseInvoiceObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('purchasemanager.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'purchasemanager'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/purchasemanager');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/purchasemanager';
        }, \Config::get('view.paths')), [$sourcePath]), 'purchasemanager');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/purchasemanager');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'purchasemanager');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'purchasemanager');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
