<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline;

use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\MapOutline;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class MapDynamicTypeOutline implements DynamicTypeOutlineInterface
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
        $name = preg_replace('/\s+/', '', $name);
        return (bool)preg_match('/^map<\S+,\S+>$/', $name);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($name)
    {
        if (!$this->isAbleToGenerate($name)) {
            throw new UnableToGenerateTypeOutlineException('Invalid name "'.$name.'" to generate map outline');
        }
        $types = $this->extractTypes($name);
        if ($types === false) {
            throw new UnableToGenerateTypeOutlineException('Invalid name "'.$name.'" to generate map outline');
        }
        list($keyTypeName, $valueTypeName) = $types;

        $keyTypeOutline = $this->schemaOutline->getTypeOutline($keyTypeName);
        $valueTypeOutline = $this->schemaOutline->getTypeOutline($valueTypeName);

        $name = vsprintf('map<%s,%s>', array(
            $keyTypeOutline->getName(),
            $valueTypeOutline->getName()
        ));

        return new MapOutline($name, $keyTypeOutline, $valueTypeOutline);
    }

    private function extractTypes($name)
    {
        $length = strlen($name);
        $current = 2;

        $opened = 0;
        $types = array();
        $buffer = '';
        while (++$current < $length) {
            $char = $name[$current];
            if ($char === '<') {
                $opened++;
                if ($opened === 1) {
                    continue;
                }
            } else if ($char === '>') {
                $opened--;
                if ($opened === 0) {
                    $types[] = trim($buffer);
                    $buffer = '';
                    continue;
                }
            } else if ($char === ',' && $opened === 1) {
                $types[] = trim($buffer);
                $buffer = '';
                continue;
            }
            $buffer .= $char;
        }
        return $opened === 0 ? $types : false;
    }
} 
