<?php

namespace Eliurkis\laravelcdn;

use Illuminate\Support\ServiceProvider;

/**
 * Class CdnServiceProvider.
 *
 * @category Service Provider
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 * @author  Abed Halawi <abed.halawi@vinelab.com>
 */
class CdnServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/cdn.php' => config_path('cdn.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        // implementation bindings:
        //-------------------------
        $this->app->bind(
            'Eliurkis\laravelcdn\Contracts\CdnInterface',
            'Eliurkis\laravelcdn\Cdn'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Providers\Contracts\ProviderInterface',
            'Eliurkis\laravelcdn\Providers\AwsS3Provider'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Contracts\AssetInterface',
            'Eliurkis\laravelcdn\Asset'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Contracts\FinderInterface',
            'Eliurkis\laravelcdn\Finder'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Contracts\ProviderFactoryInterface',
            'Eliurkis\laravelcdn\ProviderFactory'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Contracts\CdnFacadeInterface',
            'Eliurkis\laravelcdn\CdnFacade'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Contracts\CdnHelperInterface',
            'Eliurkis\laravelcdn\CdnHelper'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Validators\Contracts\ProviderValidatorInterface',
            'Eliurkis\laravelcdn\Validators\ProviderValidator'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Validators\Contracts\CdnFacadeValidatorInterface',
            'Eliurkis\laravelcdn\Validators\CdnFacadeValidator'
        );

        $this->app->bind(
            'Eliurkis\laravelcdn\Validators\Contracts\ValidatorInterface',
            'Eliurkis\laravelcdn\Validators\Validator'
        );

        // register the commands:
        //-----------------------
        $this->app->singleton('cdn.push', function ($app) {
            return $app->make('Eliurkis\laravelcdn\Commands\PushCommand');
        });

        $this->commands('cdn.push');

        $this->app->singleton('cdn.empty', function ($app) {
            return $app->make('Eliurkis\laravelcdn\Commands\EmptyCommand');
        });

        $this->commands('cdn.empty');

        // facade bindings:
        //-----------------

        // Register 'CdnFacade' instance container to our CdnFacade object
        $this->app->singleton('CDN', function ($app) {
            return $app->make('Eliurkis\laravelcdn\CdnFacade');
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
//        $this->app->booting(function () {
//            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
//            $loader->alias('Cdn', 'Eliurkis\laravelcdn\Facades\CdnFacadeAccessor');
//        });
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
