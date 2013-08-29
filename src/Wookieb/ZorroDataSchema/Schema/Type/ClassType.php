<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\Assert\Assert;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassType extends AbstractClassType
{
    /**
     * @var AbstractClassType
     */
    private $parentType;
    private $properties = array();

    /**
     * @param string $name name of type
     * @param string $class full name of class which is implementation of current "class" type
     * @param AbstractClassType $parentType parent "class" type
     */
    public function __construct($name, $class, AbstractClassType $parentType = null)
    {
        parent::__construct($name);
        $this->setClass($class);
        $this->parentType = $parentType;
        return $this;
    }

    /**
     * Returns "class" type object of parent class
     *
     * @return AbstractClassType
     */
    public function getParentType()
    {
        return $this->parentType;
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if ($data instanceof $this->class) {
            return $data;
        }

        if (!$this->isValidData($data)) {
            $msg = vsprintf('Invalid data to create object of instance %s. Only array, \Traversable and \stdClass allowed',
                array($this->class));
            throw new InvalidValueException($msg);
        }
        if ($data instanceof \stdClass) {
            $data = (array)$data;
        }

        try {
            if (version_compare('5.4', PHP_VERSION, '>')) {
                throw new \ReflectionException('Come on php!');
            }
            $object = $this->getReflection()->newInstanceWithoutConstructor();
        } catch (\ReflectionException $e) {
            $object = $this->fuckThisShit($this->class);
        }

        $this->setProperties($object, $data);
        return $object;
    }

    private function isValidData($data)
    {
        return is_array($data) || $data instanceof \stdClass || $data instanceof \Traversable;
    }

    protected function setProperties($object, array $data, array $skipProperties = array())
    {
        foreach ($this->properties as $propertyName => $definition) {
            if (in_array($propertyName, $skipProperties)) {
                continue;
            }
            try {
                $this->setProperty($object, $definition, isset($data[$propertyName]) ? $data[$propertyName] : null);
                $skipProperties[] = $propertyName;
            } catch (InvalidValueException $e) {
                throw new InvalidValueException('Cannot set value for property "'.$propertyName.'"', null, $e);
            }
        }
        if ($this->parentType) {
            $this->parentType->setProperties($object, $data, $skipProperties);
        }
    }

    protected function setProperty($object, PropertyDefinition $property, $propertyValue)
    {
        $setterName = $property->getSetterName();
        if ($setterName) {
            if (method_exists($object, $setterName)) {
                $object->$setterName($property->create($propertyValue));
                return;
            }
        }

        $targetPropertyName = $property->getTargetPropertyName();
        try {
            $reflectionProperty = $this->getReflectionProperty($targetPropertyName);
            $reflectionProperty->setValue($object, $property->create($propertyValue));
            return;
        } catch (\ReflectionException $e) {

        }

        $object->$targetPropertyName = $property->create($propertyValue);
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isTargetType($value)) {
            throw new InvalidValueException('Value to extract must be an object of class "'.$this->class.'"');
        }

        $data = array();
        $this->extractProperties($value, $data);
        return $data;
    }

    protected function extractProperties($object, &$data, array $skipProperties = array())
    {
        foreach ($this->properties as $propertyName => $property) {
            if (in_array($propertyName, $skipProperties)) {
                continue;
            }
            try {
                /* @var PropertyDefinition $type */
                $data[$propertyName] = $this->extractProperty($object, $property);
                $skipProperties[] = $propertyName;
            } catch (InvalidValueException $e) {
                throw new InvalidValueException('Cannot extract value of property "'.$propertyName.'"', null, $e);
            }
        }
        if ($this->parentType) {
            $this->parentType->extractProperties($object, $data, $skipProperties);
        }
        return $data;
    }

    protected function extractProperty($object, PropertyDefinition $property)
    {
        $getterName = $property->getGetterName();
        if ($getterName) {
            if (method_exists($object, $getterName)) {
                return $property->extract($object->$getterName());
            }
        }

        $targetPropertyName = $property->getTargetPropertyName();
        try {
            $reflectionProperty = $this->getReflectionProperty($targetPropertyName);
            return $property->extract($reflectionProperty->getValue($object));
        } catch (\ReflectionException $e) {

        }

        if (property_exists($object, $targetPropertyName)) {
            return $property->extract($object->{$targetPropertyName});
        }

        $msg = 'Cannot extract data for property "'.$targetPropertyName.'" since it not exist';
        throw new InvalidValueException($msg);
    }

    /**
     * Adds property definition
     *
     * @param PropertyDefinition $property
     * @return self
     */
    public function addProperty(PropertyDefinition $property)
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

    private function setClass($class)
    {
        Assert::notBlank($class, 'Class name cannot be empty');
        $this->reflection = null;
        $this->class = $class;
        return $this;
    }

    /**
     * Returns name of class that implement current "class" type
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return $value instanceof $this->class;
    }
}
