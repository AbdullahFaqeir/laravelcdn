<?php

namespace AbdullahFaqeir\LaravelCDN;

use Illuminate\Config\Repository;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnHelperInterface;
use AbdullahFaqeir\LaravelCDN\Exceptions\MissingConfigurationException;
use AbdullahFaqeir\LaravelCDN\Exceptions\MissingConfigurationFileException;

/**
 * Class CdnHelper
 * Helper class containing shared functions.
 *
 * @category General Helper
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class CdnHelper implements CdnHelperInterface
{
    /**
     * An object of the 'Repository' class that allows reading the laravel config files.
     *
     * @var \Illuminate\Config\Repository
     */
    protected Repository $configurations;

    /**
     * @param \Illuminate\Config\Repository $configurations
     */
    public function __construct(Repository $configurations)
    {
        $this->configurations = $configurations;
    }

    /**
     * Check if the config file exist and return it or
     * throw an exception.
     *
     * @return array
     * @throws Exceptions\MissingConfigurationFileException
     *
     */
    public function getConfigurations(): array
    {
        $configurations = $this->configurations->get('cdn');

        if (!$configurations) {
            throw new MissingConfigurationFileException("CDN 'config file' (cdn.php) not found");
        }

        return $configurations;
    }

    /**
     * Checks for any required configuration is missed.
     *
     * @param array $configuration
     * @param array $required
     *
     * @return void
     */
    public function validate(array $configuration, array $required): void
    {
        // search for any null or empty field to throw an exception
        $missing = '';
        foreach ($configuration as $key => $value) {
            if (empty($value) && in_array($key, $required, false)) {
                $missing .= ' '.$key;
            }
        }

        if ($missing) {
            throw new MissingConfigurationException('Missed Configuration:'.$missing);
        }
    }

    /**
     * Take url as string and return it parsed object.
     *
     * @param string $url
     *
     * @return mixed
     */
    public function parseUrl(string $url): mixed
    {
        return parse_url($url);
    }

    /**
     * check if a string starts with a string.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    /**
     * remove any extra slashes '/' from the path.
     *
     * @param string $path
     *
     * @return string
     */
    public function cleanPath(string $path): string
    {
        return rtrim(ltrim($path, '/'), '/');
    }
}
