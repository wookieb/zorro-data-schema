<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;
use Wookieb\ZorroDataSchema\Schema\Type\ChoiceType;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ChoiceTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ChoiceTypeBuilder implements TypeBuilderInterface, SchemaLinkerAwareInterface
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
        return $typeOutline instanceof ChoiceTypeOutline;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(TypeOutlineInterface $typeOutline, ImplementationInterface $implementation)
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            throw new UnableToGenerateTypeException('Invalid type outline', $typeOutline);
        }


        /** @var ChoiceTypeOutline $typeOutline */
        $outlines = $typeOutline->getTypeOutlines();
        if (count($outlines) < 2) {
            throw new UnableToGenerateTypeException('Choice type must use at least 2 types', $typeOutline);
        }

        $type = new ChoiceType();
        foreach ($outlines as $typeOutline) {
            $type->addType($typeOutline->getName(), $this->linker->generateType($typeOutline, $implementation));
        }
        return $type;
    }
} 
