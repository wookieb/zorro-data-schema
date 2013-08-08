<?php


namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style;


/**
 * Interface of naming style of setters and getters
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface StyleInterface
{
    /**
     * Generate setter name for given property
     *
     * @param string $propertyName
     * @return string
     */
    function generateSetterName($propertyName);

    /**
     * Generate getter name for given property
     *
     * @param string $propertyName
     * @return string
     */
    function generateGetterName($propertyName);
}