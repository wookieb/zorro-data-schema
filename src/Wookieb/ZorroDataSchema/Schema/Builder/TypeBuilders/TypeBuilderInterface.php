<?php


namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;

/**
 * Interface of type builders which transform "TypeOutlineInterface" objects to instances of "TypeInterface"
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface TypeBuilderInterface
{
    /**
     * Checks whether generator is able to generate type for given type outline
     *
     * @param TypeOutlineInterface $typeOutline
     * @return boolean
     */
    function isAbleToGenerate(TypeOutlineInterface $typeOutline);

    /**
     * Generates type based on his type outline
     *
     * @param TypeOutlineInterface $typeOutline
     * @param ImplementationInterface $implementation
     * @return TypeInterface
     * @throws UnableToGenerateTypeException
     */
    function generate(TypeOutlineInterface $typeOutline, ImplementationInterface $implementation);
}