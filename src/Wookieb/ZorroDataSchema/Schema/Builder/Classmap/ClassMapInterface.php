<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\ClassMap;
use Wookieb\ZorroDataSchema\Exception\NoClassForSuchTypeException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface ClassMapInterface
{
    /**
     * @param string $typeName
     * @return string
     * @throws NoClassForSuchTypeException when cannot
     */
    function getClass($typeName);

    /**
     * Register class name for given type
     *
     * @param string $typeName
     * @param string $class
     * @return self
     */
    function registerClass($typeName, $class);
}