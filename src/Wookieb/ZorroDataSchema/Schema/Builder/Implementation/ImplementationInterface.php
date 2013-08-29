<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Wookieb\ZorroDataSchema\Exception\ClassImplementationNotFoundException;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface ImplementationInterface
{
    /**
     * Returns class type options
     *
     * @param string $name type name
     * @return ClassTypeImplementation
     * @throws ClassImplementationNotFoundException
     */
    function getClassTypeImplementation($name);

    /**
     * Set class type options for given type name
     *
     * @param ClassTypeImplementation $classImplementation
     * @return self
     */
    function registerClassTypeImplementation(ClassTypeImplementation $classImplementation);

    /**
     * @param GlobalClassTypeImplementation $options
     * @return self
     */
    function setGlobalClassTypeImplementation(GlobalClassTypeImplementation $options);

    /**
     * @return GlobalClassTypeImplementation
     */
    function getGlobalClassImplementation();

    /**
     * @param ClassMapInterface $classMap
     * @return self
     */
    function setClassMap(ClassMapInterface $classMap);

    /**
     * @return ClassMapInterface
     */
    function getClassMap();
}
