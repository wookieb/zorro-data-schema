<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class MapOutline extends AbstractTypeOutline
{
    /**
     * @var TypeOutlineInterface
     */
    private $keyTypeOutline;
    /**
     * @var TypeOutlineInterface
     */
    private $valueTypeOutline;

    public function __construct($name, TypeOutlineInterface $keyType, TypeOutlineInterface $valueType)
    {
        parent::__construct($name);
        $this->keyTypeOutline = $keyType;
        $this->valueTypeOutline = $valueType;
    }

    /**
     * @return TypeOutlineInterface
     */
    public function getKeyTypeOutline()
    {
        return $this->keyTypeOutline;
    }

    /**
     * @return TypeOutlineInterface
     */
    public function getValueTypeOutline()
    {
        return $this->valueTypeOutline;
    }
} 
