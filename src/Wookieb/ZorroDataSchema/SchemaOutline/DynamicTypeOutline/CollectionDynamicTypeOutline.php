<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;


/**
 * Generates collection type outline based on format "collection[<name_of_type>]"
 * For example
 * collection[string] = collection of strings
 * collection[SomeClass] = collection of objects of instance SomeClass
 * collection[collection[string]] = collection of collections of strings :)
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionDynamicTypeOutline implements DynamicTypeOutlineInterface
{
    /**
     * @var SchemaOutlineInterface
     */
    private $schemaOutline;

    /**
     * @param SchemaOutlineInterface $schemaOutline reference to current schema outline
     */
    public function __construct(SchemaOutlineInterface $schemaOutline)
    {
        $this->schemaOutline = $schemaOutline;
    }

    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate($name)
    {
        return (bool)$this->extractTypeName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($name)
    {
        $typeName = $this->extractTypeName($name);
        if (!$typeName) {
            throw new UnableToGenerateTypeOutlineException('Undefined elements type name');
        }
        $type = $this->schemaOutline->getType($typeName);
        return new CollectionOutline($name, $type);
    }

    private function extractTypeName($name)
    {
        if (preg_match('/^(array|collection)\[(.+)\]$/i', $name, $matches)) {
            return $matches[2];
        }
    }
}