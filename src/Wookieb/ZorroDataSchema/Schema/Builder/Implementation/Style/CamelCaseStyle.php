<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CamelCaseStyle implements StyleInterface
{
    private $usePrefix = true;

    /**
     * @param bool $usePrefix indicate that prefix "set" or "get" should be insert before setter and getter name
     */
    public function __construct($usePrefix = true)
    {
        $this->usePrefix = (bool)$usePrefix;
    }

    /**
     * {@inheritDoc}
     */
    public function generateSetterName($propertyName)
    {
        if ($this->usePrefix) {
            return 'set'.$this->camelize($propertyName);
        }
        return lcfirst($this->camelize($propertyName));
    }

    /**
     * {@inheritDoc}
     */
    public function generateGetterName($propertyName)
    {
        if ($this->usePrefix) {
            return 'get'.$this->camelize($propertyName);
        }
        return lcfirst($this->camelize($propertyName));
    }

    private function camelize($value)
    {
        return preg_replace_callback('/(^|_)+([a-z])/', function ($match) {
            return strtoupper($match[2]);
        }, $value);
    }
}