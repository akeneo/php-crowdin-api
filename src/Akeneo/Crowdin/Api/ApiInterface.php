<?php

namespace Akeneo\Crowdin\Api;

use InvalidArgumentException;

/**
 * API Interface
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
interface ApiInterface
{
    /**
     * Set the parameters for the api call
     */
    public function setParameters(array $parameters): void;

    /**
     * Call the api method with provided parameters
     *
     * @throws InvalidArgumentException
     */
    public function execute();
}
