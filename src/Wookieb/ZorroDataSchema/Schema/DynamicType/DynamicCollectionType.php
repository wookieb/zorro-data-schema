<?php

namespace Wookieb\ZorroDataSchema\Schema\DynamicType;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Wookieb\ZorroDataSchema\Exception\NoSuchTypeException;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;
use Wookieb\ZorroDataSchema\Type\CollectionType;
use Wookieb\ZorroDataSchema\Type\TypeInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class DynamicCollectionType implements DynamicTypeInterface
{
    private $schema;

    public function __construct(SchemaInterface $schema)
    {
        $this->schema = $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate($name)
    {
        $typeName = $this->extractTypeName($name);
        if (!$typeName) {
            return false;
        }
        return $this->schema->hasType($typeName);
    }

    private function extractTypeName($name)
    {
        if (preg_match('/^(array|collection)\[(.+)\]$/i', $name, $matches)) {
            return $matches[2];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function generate($name)
    {
        $typeName = $this->extractTypeName($name);
        if (!$typeName) {
            throw new NoSuchTypeException('Type "'.$name.'" does not exists');
        }
        $type = new CollectionType($name);
        $type->setType($this->schema->getType($typeName));
        return $type;
    }
}