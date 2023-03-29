<?php

namespace AbdullahFaqeir\LaravelCDN;

use AbdullahFaqeir\LaravelCDN\Contracts\AssetInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnHelperInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\FinderInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\ProviderFactoryInterface;

/**
 * Class Cdn  is the manager and the main class of this package.
 *
 * @category Main Class
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class Cdn implements CdnInterface
{
    /**
     * An instance of the finder class.
     *
     * @var \AbdullahFaqeir\LaravelCDN\Contracts\FinderInterface
     */
    protected FinderInterface $finder;

    /**
     * The object that will hold the assets configurations
     * and the paths of the assets.
     *
     * @var Contracts\AssetInterface
     */
    protected AssetInterface $asset_holder;

    /**
     * @var \AbdullahFaqeir\LaravelCDN\Contracts\ProviderFactoryInterface
     */
    protected ProviderFactoryInterface $provider_factory;

    /**
     * @var \AbdullahFaqeir\LaravelCDN\Contracts\CdnHelperInterface
     */
    protected CdnHelperInterface $helper;

    /**
     * @param FinderInterface          $finder
     * @param AssetInterface           $asset_holder
     * @param ProviderFactoryInterface $provider_factory
     * @param CdnHelperInterface       $helper
     *
     * @internal param \AbdullahFaqeir\LaravelCDN\Repository $configurations
     */
    public function __construct(
        FinderInterface $finder,
        AssetInterface $asset_holder,
        ProviderFactoryInterface $provider_factory,
        CdnHelperInterface $helper
    ) {
        $this->finder = $finder;
        $this->asset_holder = $asset_holder;
        $this->provider_factory = $provider_factory;
        $this->helper = $helper;
    }

    /**
     * Will be called from the AbdullahFaqeir\LaravelCDN\PushCommand class on Fire().
     */
    public function push()
    {
        // return the configurations from the config file
        $configurations = $this->helper->getConfigurations();

        // Initialize an instance of the asset holder to read the configurations
        // then call the read(), to return all the allowed assets as a collection of files objects
        $assets = $this->finder->read($this->asset_holder->init($configurations));

        // store the returned assets in the instance of the asset holder as collection of paths
        $this->asset_holder->setAssets($assets);

        // return an instance of the corresponding Provider concrete according to the configuration
        return $this->provider_factory->create($configurations)
                                      ->upload($this->asset_holder->getAssets());
    }

    /**
     * Will be called from the AbdullahFaqeir\LaravelCDN\EmptyCommand class on Fire().
     */
    public function emptyBucket()
    {
        // return the configurations from the config file
        $configurations = $this->helper->getConfigurations();

        // return an instance of the corresponding Provider concrete according to the configuration
        return $this->provider_factory->create($configurations)
                                      ->emptyBucket();
    }
}
