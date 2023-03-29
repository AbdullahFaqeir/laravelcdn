<?php

namespace AbdullahFaqeir\LaravelCDN\Validators\Contracts;

/**
 * Interface ProviderValidatorInterface.
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface ProviderValidatorInterface
{
    public function validate(array $configuration, array $required): void;
}
