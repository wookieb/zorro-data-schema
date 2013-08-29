<?php


namespace Wookieb\ZorroDataSchema\Schema;
use Wookieb\ZorroDataSchema\Exception\TypeNotFoundException;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaInterface extends \IteratorAggregate
{
    /**
     * Checks whether type with given name exists
     *
     * @param string $typeName
     * @return boolean
     */
    function hasType($typeName);

    /**
     * Returns type for given name
     *
     * @param string $typeName
     * @return TypeInterface
     * @throws TypeNotFoundException when type with name does not exist
     */
    function getType($typeName);

    /**
     * Registers type of data for schema
     *
     * @param string $name
     * @param TypeInterface $type
     * @return self
     *
     * @throws \InvalidArgumentException when name of type is empty
     */
    function registerType($name, TypeInterface $type);
}
