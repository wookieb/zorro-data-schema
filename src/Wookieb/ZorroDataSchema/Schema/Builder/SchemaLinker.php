<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\SchemaLinkerAwareInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\TypeBuilderInterface;
use Wookieb\ZorroDataSchema\Schema\Schema;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;

/**
 * Builds schema from the schema outline and implementation
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaLinker
{
    /**
     * @var SchemaInterface
     */
    private $schema;
    private $typeBuilders = array();

    /**
     * @param TypeBuilderInterface $typeBuilder
     * @param int $priority priority of type builder - higher value means greater importance
     * @return self
     */
    public function registerTypeBuilder(TypeBuilderInterface $typeBuilder, $priority = 0)
    {
        if ($typeBuilder instanceof SchemaLinkerAwareInterface) {
            $typeBuilder->setSchemaLinker($this);
        }
        $this->typeBuilders[$priority][] = $typeBuilder;
        krsort($this->typeBuilders);
        return $this;
    }

    /**
     * Generates type using registered type builders
     * Uses the first (respecting priority of type builders) matched type builder to create a type
     *
     * @param TypeOutlineInterface $typeOutline
     * @param ImplementationInterface $implementation
     * @throws UnableToGenerateTypeException
     * @return TypeInterface
     */
    public function generateType(TypeOutlineInterface $typeOutline, ImplementationInterface $implementation)
    {
        if ($this->schema && $this->schema->hasType($typeOutline->getName())) {
            return $this->schema->getType($typeOutline->getName());
        }

        foreach ($this->typeBuilders as $builders) {
            foreach ($builders as $builder) {
                /* @var \Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\TypeBuilderInterface $builder */
                if ($builder->isAbleToGenerate($typeOutline)) {
                    return $builder->generate($typeOutline, $implementation);
                }
            }
        }

        $msg = 'There is no type builder able to generate "'.$typeOutline->getName().'" type outline';
        throw new UnableToGenerateTypeException($msg, $typeOutline);
    }

    /**
     * Sets default schema
     *
     * @param SchemaInterface $schema
     * @return self
     */
    public function setSchema(SchemaInterface $schema)
    {
        $this->schema = $schema;
        return $this;
    }

    /**
     * Performs build
     *
     * @param SchemaOutlineInterface $schemaOutline
     * @param ImplementationInterface $implementation
     * @param SchemaInterface $schema base schema used instead of default empty schema
     * @return SchemaInterface
     */
    public function build(SchemaOutlineInterface $schemaOutline, ImplementationInterface $implementation, SchemaInterface $schema = null)
    {
        $this->schema = $schema ? : ($this->schema ? : new Schema());
        foreach ($schemaOutline as $typeOutline) {
            /* @var \Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface $typeOutline */
            if ($this->schema->hasType($typeOutline->getName())) {
                continue;
            }
            $type = $this->generateType($typeOutline, $implementation);
            $this->schema->registerType($typeOutline->getName(), $type);
        }
        return $this->schema;
    }
}
