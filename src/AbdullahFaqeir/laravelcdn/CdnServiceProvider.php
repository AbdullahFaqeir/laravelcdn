<?php

namespace AbdullahFaqeir\LaravelCDN;

use Illuminate\Support\ServiceProvider;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnInterface;
use AbdullahFaqeir\LaravelCDN\Providers\Contracts\ProviderInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\AssetInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\FinderInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\ProviderFactoryInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnFacadeInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnHelperInterface;
use AbdullahFaqeir\LaravelCDN\Validators\Contracts\ProviderValidatorInterface;
use AbdullahFaqeir\LaravelCDN\Validators\Contracts\CdnFacadeValidatorInterface;
use AbdullahFaqeir\LaravelCDN\Validators\Contracts\ValidatorInterface;
use AbdullahFaqeir\LaravelCDN\Providers\AwsS3Provider;
use AbdullahFaqeir\LaravelCDN\Validators\ProviderValidator;
use AbdullahFaqeir\LaravelCDN\Validators\CdnFacadeValidator;
use AbdullahFaqeir\LaravelCDN\Validators\Validator;
use AbdullahFaqeir\LaravelCDN\Commands\PushCommand;
use AbdullahFaqeir\LaravelCDN\Commands\EmptyCommand;

/**
 * Class CdnServiceProvider.
 *
 * @category Service Provider
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 * @author   Abed Halawi <abed.halawi@vinelab.com>
 */
class CdnServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
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
    public function register(): void
    {
        // implementation bindings:
        //-------------------------
        $this->app->bind(CdnInterface::class, Cdn::class);

        $this->app->bind(ProviderInterface::class, AwsS3Provider::class);

        $this->app->bind(AssetInterface::class, Asset::class);

        $this->app->bind(FinderInterface::class, Finder::class);

        $this->app->bind(ProviderFactoryInterface::class, ProviderFactory::class);

        $this->app->bind(CdnFacadeInterface::class, CdnFacade::class);

        $this->app->bind(CdnHelperInterface::class, CdnHelper::class);

        $this->app->bind(ProviderValidatorInterface::class, ProviderValidator::class);

        $this->app->bind(CdnFacadeValidatorInterface::class, CdnFacadeValidator::class);

        $this->app->bind(ValidatorInterface::class, Validator::class);

        // register the commands:
        //-----------------------
        $this->app->singleton('cdn.push', function ($app) {
            return $app->make(PushCommand::class);
        });

        $this->commands('cdn.push');

        $this->app->singleton('cdn.empty', function ($app) {
            return $app->make(EmptyCommand::class);
        });

        $this->commands('cdn.empty');

        // facade bindings:
        //-----------------

        // Register 'CdnFacade' instance container to our CdnFacade object
        $this->app->singleton('CDN', function ($app) {
            return $app->make(CdnFacade::class);
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        //        $this->app->booting(function () {
        //            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        //            $loader->alias('Cdn', 'AbdullahFaqeir\LaravelCDN\Facades\CdnFacadeAccessor');
        //        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }
}
