<?php

namespace AbdullahFaqeir\LaravelCDN\Commands;

use Illuminate\Console\Command;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnInterface;

/**
 * Class PushCommand.
 *
 * @category Command
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 * @author   Raul Ruiz <abdullahfaqeir@gmail.com>
 */
class EmptyCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'cdn:empty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty all assets from CDN';

    /**
     * an instance of the main Cdn class.
     *
     * @var \AbdullahFaqeir\LaravelCDN\Contracts\CdnInterface
     */
    protected CdnInterface $cdn;

    /**
     * @param CdnInterface $cdn
     */
    public function __construct(CdnInterface $cdn)
    {
        $this->cdn = $cdn;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->cdn->emptyBucket();
    }
}
