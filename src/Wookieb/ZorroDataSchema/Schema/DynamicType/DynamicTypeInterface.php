<?php
namespace Wookieb\ZorroDataSchema\Schema\DynamicType;
use Wookieb\ZorroDataSchema\Exception\NoSuchTypeException;
use Wookieb\ZorroDataSchema\Type\TypeInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface DynamicTypeInterface
{
    /**
     * Check whether dynamic type is available for given name
     *
     * @param string $name
     * @return boolean
     */
    function isAbleToGenerate($name);

    /**
     * Generates type based on name
     *
     * @param string $name
     * @return TypeInterface
     * @throws NoSuchTypeException
     */
    function generate($name);
}