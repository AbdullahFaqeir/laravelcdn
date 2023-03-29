<?php

namespace AbdullahFaqeir\LaravelCDN;

use Illuminate\Support\Facades\App;
use AbdullahFaqeir\LaravelCDN\Contracts\ProviderFactoryInterface;
use AbdullahFaqeir\LaravelCDN\Providers\Contracts\ProviderInterface;
use AbdullahFaqeir\LaravelCDN\Exceptions\MissingConfigurationException;
use AbdullahFaqeir\LaravelCDN\Exceptions\UnsupportedProviderException;

/**
 * Class ProviderFactory
 * This class is responsible for creating objects from the default
 * provider found in the config file.
 *
 * @category Factory
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ProviderFactory implements ProviderFactoryInterface
{

    /**
     * Create and return an instance of the corresponding
     * Provider concrete according to the configuration.
     *
     * @param array $configurations
     *
     * @return \AbdullahFaqeir\LaravelCDN\Providers\Contracts\ProviderInterface
     */
    public function create(array $configurations = []): ProviderInterface
    {
        // get the default provider name
        $provider = $configurations['default'] ?? null;

        if (!$provider) {
            throw new MissingConfigurationException('Missing Configurations: Default Provider');
        }

        if (!class_exists($provider)) {
            throw new UnsupportedProviderException("CDN provider ($provider) is not supported");
        }

        // initialize the driver object and initialize it with the configurations
        return App::make($provider)
                  ->init($configurations);
    }
}
