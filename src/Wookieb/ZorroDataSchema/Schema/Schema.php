<?php

namespace Wookieb\ZorroDataSchema\Schema;
use Wookieb\Assert\Assert;
use Wookieb\ZorroDataSchema\Exception\TypeNotFoundException;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;


/**
 * Standard schema implementation
 *
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
        throw new TypeNotFoundException('Type "'.$typeName.'" does not exist');
    }

    /**
     * {@inheritDoc}
     */
    public function registerType($name, TypeInterface $type)
    {
        Assert::notBlank($name, 'Name of registered type cannot be empty');
        $this->types[$name] = $type;
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->types);
    }
}
