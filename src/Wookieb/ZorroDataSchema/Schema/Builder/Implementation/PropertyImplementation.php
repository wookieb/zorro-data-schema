<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Wookieb\Assert\Assert;


/**
 * Config that describes way to get access to property of object
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class PropertyImplementation
{
    private $targetPropertyName;
    private $setter;
    private $getter;
    private $name;

    /**
     * @param string $propertyName property name
     * @throws \InvalidArgumentException when name is empty
     */
    public function __construct($propertyName)
    {
        Assert::notBlank($propertyName, 'Property name cannot be empty');
        $this->name = $propertyName;
    }

    /**
     * Returns name of property
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns name of getter method
     *
     * @return string
     */
    public function getGetter()
    {
        return $this->getter;
    }

    /**
     * Returns name of setter method
     *
     * @return string
     */
    public function getSetter()
    {
        return $this->setter;
    }

    /**
     * Returns name of target property
     * {@see setTargetPropertyName()}
     * @return string
     */
    public function getTargetPropertyName()
    {
        return $this->targetPropertyName;
    }

    /**
     * Sets name of setter method
     *
     * @param string $setter
     * @return self
     * @throws \BadMethodCallException when accessors are disabled
     * @throws \InvalidArgumentException when name is empty
     */
    public function setSetter($setter = null)
    {
        Assert::nullOrNotBlank($setter, 'Setter name cannot be empty');
        $this->setter = $setter;
        return $this;
    }

    /**
     * Set name of target property
     * Target property is a real name of property in object.
     * Real name is used to get reflection of that property.
     * Using this name you can map real property name to "virtual" one
     *
     * @param string $targetPropertyName
     * @return self
     *
     * @throws \InvalidArgumentException when name is empty
     */
    public function setTargetPropertyName($targetPropertyName = null)
    {
        Assert::nullOrNotBlank($targetPropertyName, 'Target property name cannot be empty');
        $this->targetPropertyName = $targetPropertyName;
        return $this;
    }

    /**
     * Sets name of getter method
     *
     * @param string $getter
     * @return self
     *
     * @throws \InvalidArgumentException when name is empty
     * @throws \BadMethodCallException when accessors are disabled
     */
    public function setGetter($getter = null)
    {
        Assert::nullOrNotBlank($getter, 'Getter name cannot be empty');
        $this->getter = $getter;
        return $this;
    }
}
