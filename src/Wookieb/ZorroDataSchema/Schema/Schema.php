<?php

namespace Wookieb\ZorroDataSchema\Schema;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\TypeNotExistsException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class Schema implements SchemaInterface
{
    private $types = array();

    /**
     * {@inheritDoc}
     */
    public function hasType($typeName)
    {
        return isset($this->types[$typeName]);
    }

    /**
     * {@inheritDoc}
     */
    public function getType($typeName)
    {
        if (isset($this->types[$typeName])) {
            return $this->types[$typeName];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function registerType($name, TypeInterface $type)
    {
        Assertion::notBlank($name, 'Name of registered type cannot be empty');
        $this->types[$name] = $type;
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->types);
    }
}