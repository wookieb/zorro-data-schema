<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\NoClassForSuchTypeException;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Type\ClassType;
use Wookieb\ZorroDataSchema\Type\PropertyDefinition;
use Wookieb\ZorroDataSchema\Type\TypeInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassTypeBuilder implements TypeBuilderInterface, SchemaBuilderAwareInterface
{
    /**
     * @var SchemaBuilder
     */
    private $schemaBuilder;

    public function setSchemaBuilder(SchemaBuilder $builder)
    {
        $this->schemaBuilder = $builder;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate(TypeOutlineInterface $typeOutline)
    {
        return $typeOutline instanceof ClassOutline;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(TypeOutlineInterface $typeOutline, Implementation $implementation)
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            throw new UnableToGenerateTypeException('Invalid type outline', $typeOutline);
        }

        $parentType = null;
        /** @var ClassOutline $typeOutline */
        if ($typeOutline->getParentClass()) {
            $parentType = $this->schemaBuilder->generateType($typeOutline->getParentClass());
        }

        try {
            $classOptions = $implementation->getClassOptions($typeOutline->getName());

            $classType = new ClassType(
                $typeOutline->getName(),
                $classOptions->getClassName(),
                $parentType
            );

            foreach ($typeOutline->getProperties() as $property) {
                /* @var \Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline $property */
                $propertyType = $this->schemaBuilder->generateType($property->getType());
                $propertyDefinition = new PropertyDefinition($property->getName(), $propertyType);
                if ($property->hasDefaultValue()) {
                    $propertyDefinition->setDefaultValue($property->getDefaultValue());
                }
                $propertyDefinition->setIsNullable($property->isNullable());
                $classType->addProperty($propertyDefinition);
            }
            return $classType;
        } catch (NoClassForSuchTypeException $e) {
            $msg = 'Cannot find class name for "'.$typeOutline->getName().'" type outline';
            throw new UnableToGenerateTypeException($msg, $typeOutline, $e);
        }
    }
}