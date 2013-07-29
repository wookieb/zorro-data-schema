<?php

namespace Wookieb\ZorroDataSchema\Schema;
use Wookieb\ZorroDataSchema\Schema\DynamicType\DynamicTypeInterface;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\NoSuchTypeException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class Schema implements SchemaInterface
{
    private $dynamicTypes = array();
    private $types = array();

    public function registerDynamicType(DynamicTypeInterface $dynamicType)
    {
        $this->dynamicTypes[] = $dynamicType;
        return $this;
    }

    private function isAbleToGenerate($name)
    {
        foreach ($this->dynamicTypes as $dynamicType) {
            /* @var DynamicTypeInterface $dynamicType */
            if ($dynamicType->isAbleToGenerate($name)) {
                return true;
            }
        }
        return false;
    }

    private function generateType($name)
    {
        foreach ($this->dynamicTypes as $dynamicType) {
            /* @var DynamicTypeInterface $dynamicType */
            if ($dynamicType->isAbleToGenerate($name)) {
                return $dynamicType->generate($name);
            }
        }
        throw new NoSuchTypeException('Type "'.$name.'" does not exists');
    }

    /**
     * {@inheritDoc}
     */
    public function hasType($typeName)
    {
        return isset($this->types[$typeName]) || $this->isAbleToGenerate($typeName);
    }

    /**
     * {@inheritDoc}
     */
    public function getType($typeName)
    {
        if (isset($this->types[$typeName])) {
            return $this->types[$typeName];
        }
        $type = $this->generateType($typeName);
        $this->registerType($type);
        return $type;
    }

    /**
     * {@inheritDoc}
     */
    public function registerType(TypeInterface $type)
    {
        foreach ((array)$type->getName() as $name) {
            $this->types[$name] = $type;
        }
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->types);
    }
}