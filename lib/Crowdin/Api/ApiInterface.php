<?php

namespace Crowdin\Api;

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
}
