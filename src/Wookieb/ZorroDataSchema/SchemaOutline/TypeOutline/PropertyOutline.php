<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;
use Wookieb\Assert\Assert;

/**
 * Outline of class property
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class PropertyOutline
{
    private $name;
    private $type;
    private $nullable = false;
    private $defaultValue;
    private $hasDefaultValue = false;

    /**
     * @param string $name name of property
     * @param TypeOutlineInterface $type
     * @param boolean $nullable
     *
     * @throws \InvalidArgumentException when name is invalid
     */
    public function __construct($name, TypeOutlineInterface $type, $nullable = false)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setIsNullable($nullable);
    }

    /**
     * @return boolean
     */
    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @throws \BadMethodCallException when property is nullable
     * @return self
     */
    public function setDefaultValue($defaultValue)
    {
        if ($this->nullable) {
            throw new \BadMethodCallException('Cannot set default value of property since it\'s nullable');
        }
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

    private function setName($name)
    {
        Assert::notBlank($name, 'Name of property cannot be empty');
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
     * Set information whether property is allowed to be null
     *
     * @param boolean $nullable
     * @throws \BadMethodCallException when property has default value
     * @return self
     */
    public function setIsNullable($nullable)
    {
        $nullable = (bool)$nullable;
        if ($this->hasDefaultValue && $nullable) {
            throw new \BadMethodCallException('Cannot set property to be nullable since it has default value');
        }
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * Returns information whether property is allowed to be null
     *
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
