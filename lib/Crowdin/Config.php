<?php

/**
 * This file is part of php-crowdin-api
 *
 * Copyright (c) 2013 Akeneo
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE
 */

namespace Crowdin;

/**
 * @author Aaron Scherer <aequasi@gmail.com>
 */
class Config
{
    /**
     * An array of configuration values
     *
     * @type array
     */
    private $data = [
        'format' => 'xml'
    ];

    /**
     * Builds the data parameter, and validates
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;

        $this->validate();
    }

    /**
     * Fetches data from the config, using the given key.
     * If the key doesn't exist in the Config, it will attempt to use a default value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } elseif (null !== $default) {
            return $default;
        }

        throw new \InvalidArgumentException("Given key, '{$key}', does not exist in the config.");
    }

    /**
     * Validates the configuration parameters
     *
     * @throws \Exception
     */
    private function validate()
    {
        if (!array_key_exists('identifier', $this->data)) {
            throw new \Exception("Require key, identifier, is missing from the config.");
        }

        if (!array_key_exists('apiKey', $this->data)) {
            throw new \Exception("Require key, apiKey, is missing from the config.");
        }

        if (!in_array($this->data['format'], ['xml', 'json'])) {
            throw new \Exception("Value passed for 'format' is not valid. Must be either 'xml', or 'json'.");
        }
    }
}
 