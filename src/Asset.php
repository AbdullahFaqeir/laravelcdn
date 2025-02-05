<?php

namespace AbdullahFaqeir\LaravelCDN;

use Illuminate\Support\Collection;
use AbdullahFaqeir\LaravelCDN\Contracts\AssetInterface;

/**
 * Class Asset *  used to parse and hold all assets and
 * paths related data and configurations.
 *
 * @category DTO
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class Asset implements AssetInterface
{
    /**
     * default [include] configurations.
     *
     * @var array
     */
    protected array $default_include = [
        'directories' => ['public'],
        'extensions'  => [],
        'patterns'    => [],
    ];

    /**
     * default [exclude] configurations.
     *
     * @var array
     */
    protected array $default_exclude = [
        'directories' => [],
        'files'       => [],
        'extensions'  => [],
        'patterns'    => [],
        'hidden'      => true,
    ];

    /**
     * @var mixed
     */
    protected mixed $included_directories;

    /**
     * @var mixed
     */
    protected mixed $included_files;

    /**
     * @var mixed
     */
    protected mixed $included_extensions;

    /**
     * @var mixed
     */
    protected mixed $included_patterns;

    /**
     * @var mixed
     */
    protected mixed $excluded_directories;

    /**
     * @var mixed
     */
    protected mixed $excluded_files;

    /**
     * @var mixed
     */
    protected mixed $excluded_extensions;

    /**
     * @var mixed
     */
    protected mixed $excluded_patterns;

    /*
     * @var boolean
     */
    protected mixed $exclude_hidden;

    /*
     * Allowed assets for upload (found in included_directories)
     *
     * @var Collection
     */
    public Collection $assets;

    /**
     * build a Asset object that contains the assets related configurations.
     *
     * @param array $configurations
     *
     * @return $this
     */
    public function init($configurations = []): static
    {
        $this->parseAndFillConfiguration($configurations);

        $this->included_directories = $this->default_include['directories'];
        $this->included_extensions = $this->default_include['extensions'];
        $this->included_patterns = $this->default_include['patterns'];

        $this->excluded_directories = $this->default_exclude['directories'];
        $this->excluded_files = $this->default_exclude['files'];
        $this->excluded_extensions = $this->default_exclude['extensions'];
        $this->excluded_patterns = $this->default_exclude['patterns'];
        $this->exclude_hidden = $this->default_exclude['hidden'];

        return $this;
    }

    /**
     * Check if the config file has any missed attribute, and if any attribute
     * is missed will be overridden by a default attribute defined in this class.
     *
     * @param $configurations
     */
    private function parseAndFillConfiguration($configurations): void
    {
        $this->default_include = isset($configurations['include']) ? array_merge($this->default_include, $configurations['include']) : $this->default_include;

        $this->default_exclude = isset($configurations['exclude']) ? array_merge($this->default_exclude, $configurations['exclude']) : $this->default_exclude;
    }

    /**
     * @return mixed
     */
    public function getIncludedDirectories(): mixed
    {
        return $this->included_directories;
    }

    /**
     * @return mixed
     */
    public function getIncludedExtensions(): mixed
    {
        return $this->included_extensions;
    }

    /**
     * @return mixed
     */
    public function getIncludedPatterns(): mixed
    {
        return $this->included_patterns;
    }

    /**
     * @return mixed
     */
    public function getExcludedDirectories(): mixed
    {
        return $this->excluded_directories;
    }

    /**
     * @return mixed
     */
    public function getExcludedFiles(): mixed
    {
        return $this->excluded_files;
    }

    /**
     * @return mixed
     */
    public function getExcludedExtensions(): mixed
    {
        return $this->excluded_extensions;
    }

    /**
     * @return mixed
     */
    public function getExcludedPatterns(): mixed
    {
        return $this->excluded_patterns;
    }

    /**
     * @return Collection
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    /**
     * @param mixed $assets
     */
    public function setAssets($assets): void
    {
        $this->assets = $assets;
    }

    /**
     * @return mixed
     */
    public function getExcludeHidden(): mixed
    {
        return $this->exclude_hidden;
    }
}
