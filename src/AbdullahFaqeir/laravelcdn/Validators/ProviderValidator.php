<?php

namespace AbdullahFaqeir\LaravelCDN\Validators;

use AbdullahFaqeir\LaravelCDN\Exceptions\MissingConfigurationException;
use AbdullahFaqeir\LaravelCDN\Validators\Contracts\ProviderValidatorInterface;

/**
 * Class ProviderValidator.
 *
 * @category
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ProviderValidator extends Validator implements ProviderValidatorInterface
{
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
}
