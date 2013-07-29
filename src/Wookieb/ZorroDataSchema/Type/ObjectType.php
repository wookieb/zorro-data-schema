<?php

namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Type\PropertyDefinition\PropertyDefinitionInterface;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;
use Wookieb\ZorroDataSchema\Type\TypeInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ObjectType implements TypeInterface
{
    private $name;
    private $properties = array();
    private $class;
    private $validationErrors = array();
    private $reflection;
    private $reflectionProperties = array();


    public function __construct($name)
    {
        $name = array_map('trim', (array)$name);
        $name = array_filter($name);
        if ($name === array()) {
            throw new \InvalidArgumentException('Object type name cannot be blank');
        }
        $this->name = $name;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
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

        $object = $this->getReflection()->newInstanceWithoutConstructor();
        foreach ($this->properties as $propertyName => $definition) {
            /* @var PropertyDefinitionInterface $definition */
            $reflectionProperty = $this->getReflectionProperty($propertyName);
            $propertyValue = isset($data[$propertyName]) ? $data[$propertyName] : null;
            $reflectionProperty->setValue($object, $definition->create($propertyValue));
        }
        return $object;
    }

    /**
     * @return \ReflectionClass
     */
    private function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = new \ReflectionClass($this->class);
        }
        return $this->reflection;
    }

    /**
     * @param string $name
     * @return \ReflectionProperty
     */
    private function getReflectionProperty($name)
    {
        $reflection = $this->getReflection();
        if (!isset($this->reflectionProperties[$name])) {
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $this->reflectionProperties[$name] = $property;
        }
        return $this->reflectionProperties[$name];
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
        foreach ($this->properties as $propertyName => $definition) {
            /* @var PropertyDefinitionInterface $definition */
            $reflectionProperty = $this->getReflectionProperty($propertyName);
            $data[$propertyName] = $definition->extract($reflectionProperty->getValue($value));
        }
        return $data;
    }

    /**
     * Set property definition
     *
     * @param string $name property nae
     * @param PropertyDefinitionInterface $definition
     * @return self
     */
    public function setProperty($name, PropertyDefinitionInterface $definition)
    {
        $this->properties[$name] = $definition;
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
    public function isValid()
    {
        $this->validationErrors = array();
        if (!$this->name) {
            $this->validationErrors[] = 'Name of type not provided';
        }
        if (!$this->class) {
            $this->validationErrors[] = 'Class name not provided';
        }
        return !$this->validationErrors;
    }

    /**
     * {@inheritDoc}
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return $value instanceof $this->class;
    }
}