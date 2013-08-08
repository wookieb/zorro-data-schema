<?php

namespace Wookieb\ZorroDataSchema\Type;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StyleInterface;
use Wookieb\ZorroDataSchema\Type\PropertyDefinition;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;
use Wookieb\ZorroDataSchema\Type\TypeInterface;

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
     * @var StyleInterface
     */
    private $style;


    public function __construct($name, $class, AbstractClassType $parentType = null)
    {
        parent::__construct($name);
        $this->setClass($class);
        $this->parentType = $parentType;
        return $this;
    }

    public function getParentType()
    {
        return $this->parentType;
    }

    public function setSettersAndGettersStyle(StyleInterface $style = null)
    {
        $this->style = $style;
        return $this;
    }

    public function getSettersAndGettersStyle()
    {
        return $this->style;
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if ($data instanceof $this->class) {
            return $data;
        }

        if (!is_array($data) && !$data instanceof \stdClass) {
            $msg = vsprintf('Invalid data to create object of instance %s. Only array and \stdClass allowed',
                array($this->class));
            throw new InvalidValueException($msg);
        }
        if ($data instanceof \stdClass) {
            $data = (array)$data;
        }

        try {
            $object = $this->getReflection()->newInstanceWithoutConstructor();
        } catch (\ReflectionException $e) {
            $object = $this->hackThisShit($this->class);
        }

        $this->setProperties($object, $data);
        return $object;
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
        $propertyName = $property->getName();
        try {
            $reflectionProperty = $this->getReflectionProperty($propertyName);
            $reflectionProperty->setValue($object, $property->create($propertyValue));
            return;
        } catch (\ReflectionException $e) {

        }
        if ($this->style) {
            $setterMethod = $this->style->generateSetterName($propertyName);
            if (method_exists($object, $setterMethod)) {
                $object->$setterMethod($property->create($propertyValue));
                return;
            }
        }

        $object->$propertyName = $property->create($propertyValue);
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isTargetType($value)) {
            throw new InvalidValueException('Value to extract must be an object of class '.$this->class);
        }

        $data = array();
        $this->extractProperties($value, $data);
        return $data;
    }

    protected function extractProperties($object, &$data, array $skipProperties = array())
    {
        $data = array();
        foreach ($this->properties as $propertyName => $property) {
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
        $propertyName = $property->getName();
        try {
            $reflectionProperty = $this->getReflectionProperty($propertyName);
            return $property->extract($reflectionProperty->getValue($object));
        } catch (\ReflectionException $e) {

        }

        $getterMethod = 'get'.ucfirst($propertyName);
        if (method_exists($object, $getterMethod)) {
            return $property->extract($object->$getterMethod());
        }

        $msg = 'Cannot extract data for property "'.$propertyName.'" since it not exists';
        throw new InvalidValueException($msg);
    }

    /**
     * Set property definition
     *
     * @param string $name property nae
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

    /**
     * Set class name of objects that are handle by this type
     *
     * @param string $class
     * @return self
     *
     * @throws \InvalidArgumentException when class name is blank
     */
    public function setClass($class)
    {
        $class = trim($class);
        if (!$class) {
            throw new \InvalidArgumentException('Class name cannot be blank');
        }
        $this->reflection = null;
        $this->class = $class;
        return $this;
    }

    /**
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