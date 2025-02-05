<?php

namespace AbdullahFaqeir\LaravelCDN\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CdnFacadeAccessor.
 *
 * @category Facade Accessor
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class CdnFacadeAccessor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'CDN';
    }
}
