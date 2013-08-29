<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\ClassNotFoundException;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;
use Wookieb\ZorroDataSchema\Schema\Type\AbstractClassType;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Schema\Type\ClassType;
use Wookieb\ZorroDataSchema\Schema\Type\PropertyDefinition;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassTypeBuilder implements TypeBuilderInterface, SchemaLinkerAwareInterface
{
    /**
     * @var SchemaLinker
     */
    private $schemaLinker;

    public function setSchemaLinker(SchemaLinker $linker)
    {
        $this->schemaLinker = $linker;
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
    public function generate(TypeOutlineInterface $typeOutline, ImplementationInterface $implementation)
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            throw new UnableToGenerateTypeException('Invalid type outline', $typeOutline);
        }

        $parentType = null;
        /** @var ClassOutline $typeOutline */
        if ($typeOutline->getParentClass()) {
            $parentType = $this->schemaLinker->generateType($typeOutline->getParentClass(), $implementation);
            if (!$parentType instanceof AbstractClassType) {
                throw new UnableToGenerateTypeException('Parent type of class type must by an instance of class type', $typeOutline);
            }
        }

        try {
            $classOptions = $implementation->getClassTypeImplementation($typeOutline->getName());

            $classType = new ClassType(
                $typeOutline->getName(),
                $classOptions->getClassName(),
                $parentType
            );

            foreach ($typeOutline->getProperties() as $property) {
                $classType->addProperty($this->createPropertyDefinition($property, $classOptions, $implementation));
            }
            return $classType;
        } catch (ClassNotFoundException $e) {
            $msg = 'Cannot find class name for "'.$typeOutline->getName().'" type outline';
            throw new UnableToGenerateTypeException($msg, $typeOutline, $e);
        }
    }

    /**
     * @param PropertyOutline $property
     * @param ClassTypeImplementation $classOptions
     * @param ImplementationInterface $implementation
     *
     * @return PropertyDefinition
     */
    protected function createPropertyDefinition(PropertyOutline $property,
                                                ClassTypeImplementation $classOptions,
                                                ImplementationInterface $implementation)
    {
        $propertyType = $this->schemaLinker->generateType($property->getType(), $implementation);
        $propertyDefinition = new PropertyDefinition($property->getName(), $propertyType);
        $propertyImplementation = $classOptions->getImplementationForProperty($property->getName());

        // nullable
        $propertyDefinition->setIsNullable($property->isNullable());

        // default value
        if ($property->hasDefaultValue()) {
            $propertyDefinition->setDefaultValue($property->getDefaultValue());
        }

        // setters and getters
        $setter = $propertyImplementation->getSetter();
        if ($setter) {
            $propertyDefinition->setSetterName($setter);
        }

        $getter = $propertyImplementation->getGetter();
        if ($getter) {
            $propertyDefinition->setGetterName($getter);
        }
        return $propertyDefinition;
    }
}
