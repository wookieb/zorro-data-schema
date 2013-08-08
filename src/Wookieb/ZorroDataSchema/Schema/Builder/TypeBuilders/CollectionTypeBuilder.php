<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Type\CollectionType;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionTypeBuilder implements TypeBuilderInterface, SchemaBuilderAwareInterface
{
    /**
     * @var SchemaBuilder
     */
    private $schemaBuilder;

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
    public function setSchemaBuilder(SchemaBuilder $builder)
    {
        $this->schemaBuilder = $builder;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(TypeOutlineInterface $typeOutline, $implementation = 'php')
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            throw new UnableToGenerateTypeException('Invalid type outline', $typeOutline);
        }

        /* @var \Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline $typeOutline */


        $collectionElementsType = $this->schemaBuilder->generateType($typeOutline->getElementsType());
        return new CollectionType($typeOutline->getName(), $collectionElementsType);
    }

}