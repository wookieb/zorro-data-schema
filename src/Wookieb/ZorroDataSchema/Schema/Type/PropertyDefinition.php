<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\Assert\Assert;
use Wookieb\ZorroDataSchema\Exception\NoDefaultValueException;

/**
 * Definition of class property
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class PropertyDefinition
{
    private $name;
    /**
     * @var TypeInterface
     */
    private $type;
    private $hasDefaultValue = false;
    private $defaultValue;
    private $isNullable = false;
    private $setterName;
    private $getterName;
    private $targetPropertyName;

    /**
     * @param string $name
     * @param TypeInterface $type
     * @throws \InvalidArgumentException when name is invalid
     */
    public function __construct($name, TypeInterface $type)
    {
        $this->setName($name);
        $this->setTargetPropertyName($name);
        $this->setType($type);
    }

    private function setName($name)
    {
        Assert::notBlank($name, 'Name of property cannot be empty');
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type of definition
     *
     * @param TypeInterface $type
     * @return self
     */
    private function setType(TypeInterface $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return TypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns information whether current property has default value
     *
     * @return bool
     */
    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * Sets default value for current property
     * Note that this method will mark current property as "has default value"
     * and converts provided value to target type
     *
     * @param mixed $value
     * @throws \BadMethodCallException when property is nullable
     * @return self
     */
    public function setDefaultValue($value)
    {
        if ($this->isNullable) {
            throw new \BadMethodCallException('Cannot set default value of property since it\'s nullable');
        }
        if (!$this->type->isTargetType($value)) {
            $value = $this->type->create($value);
        }
        $this->defaultValue = $value;
        $this->hasDefaultValue = true;
        return $this;
    }

    /**
     * @return mixed
     * @throws NoDefaultValueException when property doesn't have default value
     */
    public function getDefaultValue()
    {
        if (!$this->hasDefaultValue) {
            throw new NoDefaultValueException('No default value');
        }
        return $this->defaultValue;
    }

    /**
     * Removes default value
     * Also removes mark "has default value" for current property
     *
     * @return self
     */
    public function removeDefaultValue()
    {
        $this->hasDefaultValue = false;
        $this->defaultValue = null;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if ($data === null) {
            if ($this->isNullable) {
                return null;
            }
            if ($this->hasDefaultValue) {
                return $this->defaultValue;
            }
        }
        return $this->type->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if ($value === null) {
            if ($this->isNullable) {
                return null;
            }
            if ($this->hasDefaultValue) {
                return $this->type->extract($this->defaultValue);
            }
        }
        return $this->type->extract($value);
    }

    /**
     * Returns information whether property value MAY be a null
     * @return boolean
     */
    public function isNullable()
    {
        return $this->isNullable;
    }

    /**
     * Allows or disallows current property value to be a null
     *
     * @param boolean $nullable
     * @throws \BadMethodCallException when property has default value
     * @return self
     */
    public function setIsNullable($nullable)
    {
        $this->isNullable = (bool)$nullable;
        if ($this->hasDefaultValue && $nullable) {
            throw new \BadMethodCallException('Cannot set property to be nullable since it has default value');
        }
        return $this;
    }

    /**
     * Sets name of method that should be called to set value of property
     *
     * @param string $setterName
     * @return self
     * @throws \InvalidArgumentException when setter name is empty
     */
    public function setSetterName($setterName)
    {
        Assert::nullOrNotBlank($setterName, 'Setter name cannot be empty');
        $this->setterName = $setterName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSetterName()
    {
        return $this->setterName;
    }

    /**
     * Sets name of method that should be called to retrieve value of property
     *
     * @param string $getterName
     * @return self
     * @throws \InvalidArgumentException when name is empty
     */
    public function setGetterName($getterName)
    {
        Assert::nullOrNotBlank($getterName, 'Getter name cannot be empty');
        $this->getterName = $getterName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGetterName()
    {
        return $this->getterName;
    }

    /**
     * Sets target property name which gives us ability to map current property to another one with different name
     *
     * @param string $targetPropertyName
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public function setTargetPropertyName($targetPropertyName)
    {
        Assert::notBlank($targetPropertyName, 'Name of target property cannot be empty');
        $this->targetPropertyName = $targetPropertyName;
        return $this;
    }

    /**
     * @see self::setTargetPropertuName()
     *
     * @return mixed
     */
    public function getTargetPropertyName()
    {
        return $this->targetPropertyName;
    }
}
