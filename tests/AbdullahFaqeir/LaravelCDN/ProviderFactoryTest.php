<?php

namespace AbdullahFaqeir\LaravelCDN\Tests;

use Mockery as M;
use AllowDynamicProperties;
use Illuminate\Support\Facades\App;
use AbdullahFaqeir\LaravelCDN\ProviderFactory;
use AbdullahFaqeir\LaravelCDN\Providers\AwsS3Provider;
use AbdullahFaqeir\LaravelCDN\Exceptions\MissingConfigurationException;

/**
 * Class ProviderFactoryTest.
 *
 * @category Test
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
#[AllowDynamicProperties]
class ProviderFactoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->provider_factory = new ProviderFactory();
    }

    public function tearDown(): void
    {
        M::close();
        parent::tearDown();
    }

    public function testCreateReturnCorrectProviderObject(): void
    {
        $configurations = ['default' => AwsS3Provider::class];

        $m_aws_s3 = M::mock(AwsS3Provider::class);

        App::shouldReceive('make')
           ->once()
           ->andReturn($m_aws_s3);

        $m_aws_s3->shouldReceive('init')
                 ->with($configurations)
                 ->once()
                 ->andReturn($m_aws_s3);

        $provider = $this->provider_factory->create($configurations);

        $this->assertEquals($provider, $m_aws_s3);
    }

    public function testCreateThrowsExceptionWhenMissingDefaultConfiguration(): void
    {
        $this->expectException(MissingConfigurationException::class);
        $configurations = ['default' => ''];

        $m_aws_s3 = M::mock(AwsS3Provider::class);

        App::shouldReceive('make')
           ->once()
           ->andReturn($m_aws_s3);

        $this->provider_factory->create($configurations);
    }
}
