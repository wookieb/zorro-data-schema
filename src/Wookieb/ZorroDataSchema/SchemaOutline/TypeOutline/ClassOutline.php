<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;
use Assert\Assertion;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassOutline extends AbstractTypeOutline
{
    /**
     * @var ClassOutline
     */
    private $parentClass;
    private $properties = array();

    public function __construct($name, array $properties = array(), ClassOutline $parentClass = null)
    {
        parent::__construct($name);
        foreach ($properties as $property) {
            $this->addProperty($property);
        }
        if ($parentClass) {
            $this->setParentClass($parentClass);
        }
    }

    /**
     * @param PropertyOutline $property
     * @return self
     */
    public function addProperty(PropertyOutline $property)
    {
        $this->properties[$property->getName()] = $property;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param ClassOutline $class
     * @return self
     */
    private function setParentClass(ClassOutline $class)
    {
        $this->parentClass = $class;
        return $this;
    }

    /**
     * @return ClassOutline
     */
    public function getParentClass()
    {
        return $this->parentClass;
    }
}