<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Wookieb\ZorroDataSchema\Exception\NoClassOptionsException;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\Style;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface ImplementationInterface
{
    /**
     * Returns class type options
     *
     * @param string $name type name
     * @return ClassOptions
     * @throws NoClassOptionsException
     */
    function getClassOptions($name);

    /**
     * Set class type options for given type name
     *
     * @param string $name
     * @param ClassOptions $classOptions
     * @return self
     */
    function setClassOptions($name, ClassOptions $classOptions);

    /**
     * @param GlobalClassOptions $options
     * @return self
     */
    function setGlobalClassOptions(GlobalClassOptions $options);

    /**
     * @param Styles $styles
     * @return self
     */
    function setStyles(Styles $styles);

    function setClassMap(ClassMapInterface $classMap);
}