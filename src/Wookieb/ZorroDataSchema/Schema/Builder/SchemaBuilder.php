<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\ClassMapAwareInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\SchemaBuilderAwareInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\TypeBuilderInterface;
use Wookieb\ZorroDataSchema\Schema\Schema;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Type\TypeInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaBuilder
{
    private $schemaOutline;
    /**
     * @var SchemaInterface
     */
    private $schema;
    private $typeBuilders = array();


    public function __construct(SchemaOutlineInterface $schemaOutline)
    {
        $this->schemaOutline = $schemaOutline;
    }

    public function registerTypeBuilder(TypeBuilderInterface $typeBuilder, $priority = 0)
    {
        if ($typeBuilder instanceof SchemaBuilderAwareInterface) {
            $typeBuilder->setSchemaBuilder($this);
        }
        $this->typeBuilders[$priority][] = $typeBuilder;
        krsort($this->typeBuilders);
        return $this;
    }

    /**
     * @param TypeOutlineInterface $typeOutline
     * @throws UnableToGenerateTypeException
     * @return TypeInterface
     */
    public function generateType(TypeOutlineInterface $typeOutline)
    {
        if ($this->schema->hasType($typeOutline->getName())) {
            return $this->schema->getType($typeOutline->getName());
        }

        foreach ($this->typeBuilders as $builders) {
            foreach ($builders as $builder) {
                /* @var \Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\TypeBuilderInterface $builder */
                if ($builder->isAbleToGenerate($typeOutline)) {
                    return $builder->generate($typeOutline);
                }
            }
        }

        $msg = 'There is no type builder able to handle "'.$typeOutline->getName().'" type outline';
        throw new UnableToGenerateTypeException($msg, $typeOutline);
    }

    public function build(SchemaInterface $schema = null)
    {
        $this->schema = $schema ? : new Schema();
        foreach ($this->schemaOutline as $typeOutline) {
            /* @var \Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface $typeOutline */
            $type = $this->generateType($typeOutline);
            $this->schema->registerType($typeOutline->getName(), $type);
        }
        return $this->schema;
    }
}