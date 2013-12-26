<?php

namespace Crowdin\Api;

use Crowdin\Client;

/**
 * API Interface
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
interface ApiInterface
{
    /**
     * Call the api method with provided parameters
     *
     * @return mixed
     */
    public function execute();

    /**
     * Set parameters
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters);

    /**
     * Get expected parameters
     *
     * @return array
     */
    public function getExpectedParameters();

}
