<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline;
use Traversable;
use Wookieb\ZorroDataSchema\Exception\TypeOutlineNotExistsException;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\DynamicTypeOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaOutline implements SchemaOutlineInterface
{
    private $types = array();
    private $dynamicTypes = array();

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->types);
    }

    /**
     * {@inheritDoc}
     */
    public function getType($name)
    {
        if ($this->isDefinedType($name)) {
            return $this->types[$name];
        }

        $dynamicType = $this->getDynamicTypeAbleToGenerate($name);
        if ($dynamicType) {
            try {
                return $dynamicType->generate($name);
            } catch (UnableToGenerateTypeOutlineException $e) {
                throw new TypeOutlineNotExistsException('Type outline with name "'.$name.'" does not exists', null, $e);
            }

        }

        throw new TypeOutlineNotExistsException('Type outline with name "'.$name.'" does not exists');
    }

    private function getDynamicTypeAbleToGenerate($name)
    {
        foreach ($this->dynamicTypes as $dynamicType) {
            /* @var DynamicTypeOutlineInterface $dynamicType */
            if ($dynamicType->isAbleToGenerate($name)) {
                return $dynamicType;
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function hasType($name)
    {
        return $this->isDefinedType($name) || (bool)$this->getDynamicTypeAbleToGenerate($name);
    }

    private function isDefinedType($name)
    {
        return isset($this->types[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function addType(TypeOutlineInterface $type)
    {
        $this->types[$type->getName()] = $type;
        return $this;
    }

    /**
     * @param DynamicTypeOutlineInterface $dynamicType
     * @return self
     */
    public function addDynamicType(DynamicTypeOutlineInterface $dynamicType)
    {
        $this->dynamicTypes[] = $dynamicType;
        return $this;
    }

    /**
     * @return \Traversable
     */
    public function getDynamicTypes()
    {
        return $this->dynamicTypes;
    }
}