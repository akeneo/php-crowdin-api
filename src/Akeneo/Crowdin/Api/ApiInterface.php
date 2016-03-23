<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;

/**
 * API Interface
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
interface ApiInterface
{
    /**
     * Set the parameters for the api call
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters);

    /**
     * Call the api method with provided parameters
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function execute();
}
