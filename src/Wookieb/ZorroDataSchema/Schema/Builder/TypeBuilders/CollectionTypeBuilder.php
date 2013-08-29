<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Schema\Type\CollectionType;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionTypeBuilder implements TypeBuilderInterface, SchemaLinkerAwareInterface
{
    /**
     * @var SchemaLinker
     */
    private $schemaLinker;

    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate(TypeOutlineInterface $typeOutline)
    {
        return $typeOutline instanceof CollectionOutline;
    }

    /**
     * {@inheritDoc}
     */
    public function setSchemaLinker(SchemaLinker $linker)
    {
        $this->schemaLinker = $linker;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(TypeOutlineInterface $typeOutline, ImplementationInterface $implementation)
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            throw new UnableToGenerateTypeException('Invalid type outline', $typeOutline);
        }

        /* @var \Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline $typeOutline */
        $collectionElementsType = $this->schemaLinker->generateType($typeOutline->getElementsType(), $implementation);
        return new CollectionType($typeOutline->getName(), $collectionElementsType);
    }

}
