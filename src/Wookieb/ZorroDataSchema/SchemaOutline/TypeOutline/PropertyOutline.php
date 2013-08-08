<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class PropertyOutline
{
    private $name;
    private $type;
    private $nullable = false;
    private $defaultValue;
    private $hasDefaultValue = false;

    public function __construct($name, TypeOutlineInterface $type)
    {
        $this->setName($name);
        $this->setType($type);
    }

    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return self
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
        $this->hasDefaultValue = true;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    private function setName($name)
    {
        $name = trim($name);
        if (!$name) {
            throw new \InvalidArgumentException('Name of property cannot be blank');
        }
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $nullable
     * @return self
     */
    public function setIsNullable($nullable)
    {
        $this->nullable = (bool)$nullable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param TypeOutlineInterface $type
     *
     * @return self
     */
    private function setType(TypeOutlineInterface $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return TypeOutlineInterface
     */
    public function getType()
    {
        return $this->type;
    }
}