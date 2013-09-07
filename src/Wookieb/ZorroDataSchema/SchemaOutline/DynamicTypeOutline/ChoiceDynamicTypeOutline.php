<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ChoiceTypeOutline;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ChoiceDynamicTypeOutline implements DynamicTypeOutlineInterface
{
    /**
     * @var SchemaOutlineInterface
     */
    private $schemaOutline;

    /**
     * @param SchemaOutlineInterface $schemaOutline reference to current outline of schema
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
        return strpos($name, '|') !== false;
    }

    private function extractTypeNames($name)
    {
        $names = explode('|', $name);
        $names = array_map('trim', $names);
        $names = array_filter($names);
        return $names;
    }

    /**
     * {@inheritDoc}
     */
    public function generate($name)
    {
        $typeNames = $this->extractTypeNames($name);
        if (count($typeNames) < 2) {
            $msg = 'Choice type "'.$name.'" defines only one type. At least 2 required';
            throw new UnableToGenerateTypeOutlineException($msg);
        }

        $name = implode('|', $typeNames);
        $outline = new ChoiceTypeOutline($name);
        foreach ($typeNames as $typeName) {
            $outline->addTypeOutline($this->schemaOutline->getTypeOutline($typeName));
        }
        return $outline;
    }
}
