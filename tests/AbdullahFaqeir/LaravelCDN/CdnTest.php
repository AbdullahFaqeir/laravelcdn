<?php

namespace AbdullahFaqeir\LaravelCDN\Tests;

use AllowDynamicProperties;
use Illuminate\Support\Collection;
use Mockery as M;
use AbdullahFaqeir\LaravelCDN\Cdn;
use Illuminate\Support\Facades\App;
use AbdullahFaqeir\LaravelCDN\Asset;
use AbdullahFaqeir\LaravelCDN\Finder;
use AbdullahFaqeir\LaravelCDN\CdnHelper;
use AbdullahFaqeir\LaravelCDN\ProviderFactory;
use Aws\S3\S3Client;
use Aws\Command;
use Symfony\Component\Finder\SplFileInfo;
use AbdullahFaqeir\LaravelCDN\Validators\Contracts\ProviderValidatorInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Config\Repository;
use AbdullahFaqeir\LaravelCDN\Contracts\CdnHelperInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\ProviderFactoryInterface;
use AbdullahFaqeir\LaravelCDN\Providers\Provider;
use AbdullahFaqeir\LaravelCDN\Contracts\FinderInterface;
use AbdullahFaqeir\LaravelCDN\Contracts\AssetInterface;

/**
 * Class CdnTest.
 *
 * @category Test
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
#[AllowDynamicProperties]
class CdnTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->m_spl_file_info = M::mock(SplFileInfo::class);
    }

    public function tearDown(): void
    {
        M::close();
        parent::tearDown();
    }

    public function testPushCommandReturnTrue(): void
    {
        $this->m_asset = M::mock(AssetInterface::class);
        $this->m_asset->shouldReceive('init')
                      ->once()
                      ->andReturn($this->m_asset);
        $this->m_asset->shouldReceive('setAssets')
                      ->once();

        $this->m_asset->shouldReceive('getAssets')
                      ->once()
                      ->andReturn(new Collection());

        $this->m_finder = M::mock(FinderInterface::class);
        $this->m_finder->shouldReceive('read')
                       ->with($this->m_asset)
                       ->once()
                       ->andReturn(new Collection());

        $this->m_provider = M::mock(Provider::class);
        $this->m_provider->shouldReceive('upload')
                         ->once()
                         ->andReturn(true);

        $this->m_provider_factory = M::mock(ProviderFactoryInterface::class);
        $this->m_provider_factory->shouldReceive('create')
                                 ->once()
                                 ->andReturn($this->m_provider);

        $this->m_helper = M::mock(CdnHelperInterface::class);
        $this->m_helper->shouldReceive('getConfigurations')
                       ->once()
                       ->andReturn([]);

        $this->cdn = new Cdn($this->m_finder, $this->m_asset, $this->m_provider_factory, $this->m_helper);

        $result = $this->cdn->push();

        $this->assertEquals($result, true);
    }

    /**
     * Integration Test.
     *
     * @return void
     */
    public function testPushCommand(): void
    {
        $configuration_file = [
            'bypass'    => false,
            'default'   => 'AwsS3',
            'url'       => 'https://s3.amazonaws.com',
            'threshold' => 10,
            'providers' => [
                'aws' => [
                    's3' => [
                        'region'     => 'us-standard',
                        'version'    => 'latest',
                        'buckets'    => [
                            'my-bucket-name' => '*',
                        ],
                        'acl'        => 'public-read',
                        'cloudfront' => [
                            'use'     => false,
                            'cdn_url' => '',
                        ],
                        'metadata'   => [],

                        'expires' => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),

                        'cache-control' => 'max-age=2628000',

                        'version' => '',
                    ],
                ],
            ],
            'include'   => [
                'directories' => [__DIR__],
                'extensions'  => [],
                'patterns'    => [],
            ],
            'exclude'   => [
                'directories' => [],
                'files'       => [],
                'extensions'  => [],
                'patterns'    => [],
                'hidden'      => true,
            ],
        ];

        $m_consol = M::mock(ConsoleOutput::class);
        $m_consol->shouldReceive('writeln')
                 ->atLeast(1);

        $finder = new Finder($m_consol);

        $asset = new Asset();

        $provider_factory = new ProviderFactory();

        $m_config = M::mock(Repository::class);
        $m_config->shouldReceive('get')
                 ->with('cdn')
                 ->once()
                 ->andReturn($configuration_file);

        $helper = new CdnHelper($m_config);

        $m_console = M::mock(ConsoleOutput::class);
        $m_console->shouldReceive('writeln')
                  ->atLeast(2);

        $m_validator = M::mock(ProviderValidatorInterface::class);
        $m_validator->shouldReceive('validate');

        $m_helper = M::mock(CdnHelper::class);

        $m_spl_file = M::mock(SplFileInfo::class);
        $m_spl_file->shouldReceive('getPathname')
                   ->andReturn('AbdullahFaqeir\LaravelCDN/tests/AbdullahFaqeir/LaravelCDN/AwsS3ProviderTest.php');
        $m_spl_file->shouldReceive('getRealPath')
                   ->andReturn(__DIR__.'/AwsS3ProviderTest.php');

        // partial mock
        $p_aws_s3_provider = M::mock('\AbdullahFaqeir\LaravelCDN\Providers\AwsS3Provider[connect]', [
            $m_console,
            $m_validator,
            $m_helper,
        ]);

        $m_s3 = M::mock(S3Client::class)
                 ->shouldIgnoreMissing();
        $m_s3->shouldReceive('factory')
             ->andReturn(S3Client::class);
        $m_command = M::mock(Command::class);
        $m_s3->shouldReceive('getCommand')
             ->andReturn($m_command);
        $m_s3->shouldReceive('execute');

        $p_aws_s3_provider->setS3Client($m_s3);

        $p_aws_s3_provider->shouldReceive('connect')
                          ->andReturn(true);

        App::shouldReceive('make')
           ->once()
           ->andReturn($p_aws_s3_provider);

        $cdn = new Cdn($finder, $asset, $provider_factory, $helper);

        $result = $cdn->push();

        $this->assertEquals($result, true);
    }
}
