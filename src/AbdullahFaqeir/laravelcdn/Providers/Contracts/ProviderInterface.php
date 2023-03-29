<?php

namespace AbdullahFaqeir\LaravelCDN\Providers\Contracts;

use Aws\S3\S3ClientInterface;

/**
 * Interface ProviderInterface.
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface ProviderInterface
{
    public function init(array $configurations);

    public function upload($assets);

    public function emptyBucket();

    public function urlGenerator(string $path): string;

    public function getUrl();

    public function getCloudFront();

    public function getCloudFrontUrl();

    public function getBucket();

    public function setS3Client(S3ClientInterface $s3_client);
}
