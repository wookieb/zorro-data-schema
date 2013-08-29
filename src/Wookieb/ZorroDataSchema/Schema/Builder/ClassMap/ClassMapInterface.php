<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\ClassMap;
use Wookieb\ZorroDataSchema\Exception\ClassNotFoundException;


/**
 * Handle mapping of types to class names
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface ClassMapInterface
{
    /**
     * Performs search to get class name for given type name
     * The order of searching is:
     * - map of registered class for type names
     * - list of registered namespaces
     *
     * @param string $typeName
     * @return string
     * @throws ClassNotFoundException when class cannot be found
     */
    function getClass($typeName);

    /**
     * Registers class name for given type
     *
     * @param string $typeName
     * @param string $class
     * @return self
     */
    function registerClass($typeName, $class);

    /**
     * Registers namespace to search for classes
     *
     * @param string $namespace
     * @return self
     */
    function registerNamespace($namespace);
}
