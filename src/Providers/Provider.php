<?php

namespace AbdullahFaqeir\LaravelCDN\Providers;

use Symfony\Component\Console\Output\ConsoleOutput;
use AbdullahFaqeir\LaravelCDN\Providers\Contracts\ProviderInterface;

/**
 * Class Provider.
 *
 * @category Drivers Abstract Class
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
abstract class Provider implements ProviderInterface
{
    /**
     * @var string
     */
    protected string $key;

    /**
     * @var string
     */
    protected string $secret;

    /**
     * @var string
     */
    protected string $region;

    /**
     * @var string
     */
    protected string $version;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var \Symfony\Component\Console\Output\ConsoleOutput of the console object
     */
    public ConsoleOutput $console;

    abstract public function upload($assets);
}
