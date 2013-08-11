<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline;
use Wookieb\ZorroDataSchema\Exception\TypeOutlineNotFoundException;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\DynamicTypeOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaOutlineInterface extends \IteratorAggregate
{
    /**
     * Returns type outline for given name
     *
     * @param string $name
     * @return TypeOutlineInterface
     * @throws TypeOutlineNotFoundException
     */
    function getType($name);

    /**
     * Check whether type outline with given name exists
     *
     * @param string $name
     * @return boolean
     */
    function hasType($name);

    /**
     * Add new type outline
     *
     * @param TypeOutlineInterface $type
     * @return self
     */
    function addType(TypeOutlineInterface $type);

    /**
     * Add dynamic type which generate type outline based on name
     * Dynamic types are used when type we are looking for cannot be found on list of registered type outlines
     *
     * @param DynamicTypeOutlineInterface $dynamicType
     * @return self
     */
    function addDynamicType(DynamicTypeOutlineInterface $dynamicType);
}
