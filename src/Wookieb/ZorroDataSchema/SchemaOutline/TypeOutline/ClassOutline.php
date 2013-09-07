<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;

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
     * Set type outline of parent class
     *
     * @param ClassOutline $class
     * @return self
     */
    private function setParentClass(ClassOutline $class)
    {
        $this->parentClass = $class;
        return $this;
    }

    /**
     * Returns type outline of parent class
     *
     * @return ClassOutline
     */
    public function getParentClass()
    {
        return $this->parentClass;
    }

    /**
     * Checks whether current class outline is a subclass of class outline with given name
     *
     * @param string $name
     * @return boolean
     */
    public function isSubclassOf($name)
    {
        return $this->parentClass && ($this->parentClass->getName() === $name || $this->parentClass->isSubclassOf($name));
    }
}
