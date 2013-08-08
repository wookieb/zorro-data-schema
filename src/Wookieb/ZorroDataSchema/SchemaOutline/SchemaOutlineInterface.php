<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline;
use Wookieb\ZorroDataSchema\Exception\TypeOutlineNotExistsException;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\DynamicTypeOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaOutlineInterface extends \IteratorAggregate
{
    /**
     * @param string $name
     * @return TypeOutlineInterface
     * @throws TypeOutlineNotExistsException
     */
    function getType($name);

    /**
     * @param string $name
     * @return boolean
     */
    function hasType($name);

    /**
     * @param TypeOutlineInterface $type
     * @return self
     */
    function addType(TypeOutlineInterface $type);

    /**
     * @param DynamicTypeOutlineInterface $dynamicType
     * @return self
     */
    function addDynamicType(DynamicTypeOutlineInterface $dynamicType);

    /**
     * @return \Traversable
     */
    function getDynamicTypes();
}
