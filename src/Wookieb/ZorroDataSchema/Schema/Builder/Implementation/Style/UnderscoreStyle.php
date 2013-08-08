<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style;


/**
 * Underscore style of setters
 * For exampla:
 * $this->generateSetterName('CamelCase'); // camel_case
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class UnderscoreStyle implements StyleInterface
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
            return 'set_'.$this->underscorize($propertyName);
        }
        return $this->underscorize($propertyName);
    }

    /**
     * {@inheritDoc}
     */
    public function generateGetterName($propertyName)
    {
        if ($this->usePrefix) {
            return 'get_'.$this->underscorize($propertyName);
        }
        return $this->underscorize($propertyName);
    }

    private function underscorize($value)
    {
        $value = preg_replace_callback('/([A-Z][a-z]+)/', function ($match) {
            return '_'.lcfirst($match[1]);
        }, $value);
        return ltrim($value, '_');
    }
}