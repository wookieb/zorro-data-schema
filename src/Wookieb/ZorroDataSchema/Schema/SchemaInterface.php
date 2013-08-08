<?php


namespace Wookieb\ZorroDataSchema\Schema;
use Wookieb\ZorroDataSchema\Exception\TypeNotExistsException;
use Wookieb\ZorroDataSchema\Type\TypeInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaInterface extends \IteratorAggregate
{
    /**
     * Check whether type with given name exists
     *
     * @param string $typeName
     * @return boolean
     */
    function hasType($typeName);

    /**
     * Return type for given name
     *
     * @param string $typeName
     * @return TypeInterface
     * @throws TypeNotExistsException when type with name does not exists
     */
    function getType($typeName);

    /**
     * Register type of data for schema
     *
     * @param string $name
     * @param TypeInterface $type
     * @return self
     */
    function registerType($name, TypeInterface $type);
}