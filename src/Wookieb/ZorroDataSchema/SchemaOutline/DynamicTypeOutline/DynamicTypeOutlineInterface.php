<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface DynamicTypeOutlineInterface
{
    /**
     * Checks whether current object is able to generate TypeOutline for given name
     *
     * @param string $name
     * @return boolean
     */
    function isAbleToGenerate($name);

    /**
     * Generates TypeOutline based on given name
     *
     * @param string $name
     * @return TypeOutlineInterface
     * @throws UnableToGenerateTypeOutlineException
     */
    function generate($name);
}