<?php


namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Implementation;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface TypeBuilderInterface
{
    /**
     * @param TypeOutlineInterface $typeOutline
     * @return boolean
     */
    function isAbleToGenerate(TypeOutlineInterface $typeOutline);

    /**
     * @param TypeOutlineInterface $typeOutline
     * @param string $implementation
     * @return TypeInterface
     * @throws UnableToGenerateTypeException
     */
    function generate(TypeOutlineInterface $typeOutline, Implementation $implementation);
}