<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\EnumOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Type\EnumType;
use Wookieb\ZorroDataSchema\Type\TypeInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class EnumTypeBuilder implements TypeBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate(TypeOutlineInterface $typeOutline)
    {
        return $typeOutline instanceof EnumOutline;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(TypeOutlineInterface $typeOutline, $implementation = 'php')
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            throw new UnableToGenerateTypeException('Invalid type outline', $typeOutline);
        }

        /* @var \Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\EnumOutline $typeOutline */
        return new EnumType($typeOutline->getName(), $typeOutline->getOptions());
    }
}