<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Schema\Type\MapType;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\MapOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class MapTypeBuilder implements TypeBuilderInterface, SchemaLinkerAwareInterface
{
    /**
     * @var SchemaLinker
     */
    private $linker;

    public function setSchemaLinker(SchemaLinker $linker)
    {
        $this->linker = $linker;
    }

    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate(TypeOutlineInterface $typeOutline)
    {
        return $typeOutline instanceof MapOutline;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(TypeOutlineInterface $typeOutline, ImplementationInterface $implementation)
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            throw new UnableToGenerateTypeException('Invalid type outline', $typeOutline);
        }

        /** @var MapOutline $typeOutline */
        $keyType = $this->linker->generateType($typeOutline->getKeyTypeOutline(), $implementation);
        $valueType = $this->linker->generateType($typeOutline->getValueTypeOutline(), $implementation);

        return new MapType($keyType, $valueType);
    }
} 
