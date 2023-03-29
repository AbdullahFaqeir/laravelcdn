<?php

namespace AbdullahFaqeir\LaravelCDN\Contracts;

/**
 * Interface CdnFacadeInterface.
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface CdnFacadeInterface
{
    public function asset(string $path): mixed;
}
