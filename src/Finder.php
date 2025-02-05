<?php

namespace AbdullahFaqeir\LaravelCDN;

use Illuminate\Support\Collection;
use AbdullahFaqeir\LaravelCDN\Contracts\AssetInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\FinderInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Finder\Finder as SymfonyFinder;

/**
 * Class Finder.
 *
 * @category Finder Helper
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class Finder extends SymfonyFinder implements FinderInterface
{
    /**
     * @var \Symfony\Component\Console\Output\ConsoleOutput
     */
    protected ConsoleOutput $console;

    /**
     * @param ConsoleOutput $console
     */
    public function __construct(ConsoleOutput $console)
    {
        $this->console = $console;

        parent::__construct();
    }

    /**
     * return a collection of arrays of assets paths found
     * in the included directories, except all ignored
     * (directories, patterns, extensions and files).
     *
     * @param AssetInterface $paths
     *
     * @return Collection
     */
    public function read(AssetInterface $paths): Collection
    {
        /*
         * add the included directories and files
         */
        $this->includeThis($paths);
        /*
         * exclude the ignored directories and files
         */
        $this->excludeThis($paths);

        // user terminal message
        $this->console->writeln('<fg=yellow>Files to upload:</fg=yellow>');

        // get all allowed 'for upload' files objects (assets) and store them in an array
        $assets = [];
        foreach ($this->files() as $file) {
            // user terminal message
            $this->console->writeln('<fg=cyan>'.'Path: '.$file->getRealpath().'</fg=cyan>');

            $assets[] = $file;
        }

        return new Collection($assets);
    }

    /**
     * Add the included directories and files.
     *
     * @param AssetInterface $asset_holder
     */
    private function includeThis(AssetInterface $asset_holder): void
    {
        // include the included directories
        $this->in($asset_holder->getIncludedDirectories());

        // include files with this extensions
        foreach ($asset_holder->getIncludedExtensions() as $extension) {
            $this->name('*'.$extension);
        }

        // include patterns
        foreach ($asset_holder->getIncludedPatterns() as $pattern) {
            $this->name($pattern);
        }

        // exclude ignored directories
        $this->exclude($asset_holder->getExcludedDirectories());
    }

    /**
     * exclude the ignored directories and files.
     *
     * @param AssetInterface $asset_holder
     */
    private function excludeThis(AssetInterface $asset_holder): void
    {
        // add or ignore hidden directories
        $this->ignoreDotFiles($asset_holder->getExcludeHidden());

        // exclude ignored files
        foreach ($asset_holder->getExcludedFiles() as $name) {
            $this->notName($name);
        }

        // exclude files (if exist) with this extensions
        $excluded_extensions = $asset_holder->getExcludedExtensions();
        if (!empty($excluded_extensions)) {
            foreach ($asset_holder->getExcludedExtensions() as $extension) {
                $this->notName('*'.$extension);
            }
        }

        // exclude the regex pattern
        foreach ($asset_holder->getExcludedPatterns() as $pattern) {
            $this->notName($pattern);
        }
    }
}
