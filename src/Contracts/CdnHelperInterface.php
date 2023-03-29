<?php

namespace AbdullahFaqeir\LaravelCDN\Contracts;

/**
 * Interface CdnHelperInterface.
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface CdnHelperInterface
{
    public function getConfigurations();

    public function validate(array $configuration, array $required);

    public function parseUrl(string $url);

    public function startsWith(string $haystack, string $needle);

    public function cleanPath(string $path);
}
