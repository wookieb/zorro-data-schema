<?php

namespace Wookieb\ZorroDataSchema\Type;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Type\PropertyDefinition\PropertyDefinitionInterface;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\NoDefaultValueException;

// TODO dodać name
/**
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


    public function __construct($name, TypeInterface $type)
    {
        $this->setName($name);
        $this->setType($type);
    }

    private function setName($name)
    {
        Assertion::notBlank($name, 'Property name cannot be empty');
        $this->name = $name;
    }

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
    public function setType(TypeInterface $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultValue($value)
    {
        if (!$this->type->isTargetType($value)) {
            $value = $this->type->create($value);
        }
        $this->defaultValue = $value;
        $this->hasDefaultValue = true;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultValue()
    {
        if (!$this->hasDefaultValue) {
            throw new NoDefaultValueException('No default value');
        }
        return $this->defaultValue;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function isNullable()
    {
        return $this->isNullable;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsNullable($nullable)
    {
        $this->isNullable = (bool)$nullable;
        if ($this->isNullable) {
            $this->removeDefaultValue();
        }
        return $this;
    }
}