<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionDynamicTypeOutline implements DynamicTypeOutlineInterface
{
    /**
     * @var SchemaOutlineInterface
     */
    private $schemaOutline;

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