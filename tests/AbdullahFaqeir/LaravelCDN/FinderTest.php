<?php

namespace AbdullahFaqeir\LaravelCDN\Tests;

use AllowDynamicProperties;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use Mockery as M;
use AbdullahFaqeir\LaravelCDN\Asset;
use AbdullahFaqeir\LaravelCDN\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class FinderTest.
 *
 * @category Test
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
#[AllowDynamicProperties]
class FinderTest extends TestCase
{
    public function tearDown(): void
    {
        M::close();
        parent::tearDown();
    }

    public function testReadReturnCorrectDataType(): void
    {
        $asset_holder = new Asset();

        $asset_holder->init([
            'include' => [
                'directories' => [__DIR__],
            ],
        ]);

        $console_output = M::mock(ConsoleOutput::class);
        $console_output->shouldReceive('writeln')
                       ->atLeast(1);

        $finder = new Finder($console_output);

        $result = $finder->read($asset_holder);

        $this->assertInstanceOf(SplFileInfo::class, $result->first());

        $this->assertEquals($result, new Collection($result->all()));
    }

    public function testReadThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $asset_holder = new Asset();

        $asset_holder->init(['include' => []]);

        $console_output = M::mock(ConsoleOutput::class);
        $console_output->shouldReceive('writeln')
                       ->atLeast(1);

        $finder = new Finder($console_output);

        $finder->read($asset_holder);
    }
}
