<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\Assert\Assert;


/**
 * Base class for "class" type
 * Every custom implementation of "class" type should extend this class
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class AbstractClassType implements TypeInterface
{
    private $name;
    protected $class;
    protected $reflection;
    protected $reflectionProperties = array();

    /**
     * @param string $name name of property
     *
     * @throws \InvalidArgumentException when name is invalid
     */
    public function __construct($name)
    {
        Assert::notBlank($name, 'Name of class type cannot be empty');
        $this->name = $name;

        $this->init();
    }

    /**
     * Just init method called at the end of constructor
     */
    protected function init()
    {

    }

    /**
     * Returns name of "class" type
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set values of properties for given object
     *
     * @param object $object
     * @param array $data data to set
     * @param array $skipProperties list of properties that MUST be omitted
     */
    abstract protected function setProperties($object, array $data, array $skipProperties = array());

    /**
     * Extracts properties from object
     *
     * @param object $object
     * @param array $data reference to data container where we put properties value
     * @param array $skipProperties list of properties that MUST be omitted
     */
    abstract protected function extractProperties($object, &$data, array $skipProperties = array());

    /**
     * @return \ReflectionClass
     */
    protected function getReflection()
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
    protected function getReflectionProperty($name)
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
     * https://gist.github.com/wookieb/6149469
     *
     * @return mixed
     */
    protected function fuckThisShit()
    {
        $reflector = $this->getReflection();
        $properties = $reflector->getProperties();
        $defaults = $reflector->getDefaultProperties();

        $serialized = "O:".strlen($this->class).":\"$this->class\":".count($properties).':{';
        foreach ($properties as $property) {
            $name = $property->getName();
            if ($property->isProtected()) {
                $name = chr(0).'*'.chr(0).$name;
            } elseif ($property->isPrivate()) {
                $name = chr(0).$this->class.chr(0).$name;
            }
            $serialized .= serialize($name);
            if (array_key_exists($property->getName(), $defaults)) {
                $serialized .= serialize($defaults[$property->getName()]);
            } else {
                $serialized .= serialize(null);
            }
        }
        $serialized .= "}";
        return unserialize($serialized);
    }
}
