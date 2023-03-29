<?php

namespace AbdullahFaqeir\LaravelCDN\Contracts;

/**
 * Interface ProviderFactoryInterface.
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface ProviderFactoryInterface
{
    public function create(array $configurations);
}
