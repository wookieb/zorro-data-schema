<?php
namespace Wookieb\ZorroDataSchema\Type\Container;
use Traversable;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\NoSuchDefinitionException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class TypesContainer implements TypesContainerInterface
{
    private $definitions = array();

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->definitions);
    }

    /**
     * {@inheritDoc}
     */
    function getType($name)
    {
        if (!isset($this->definitions[$name])) {
            throw new NoSuchDefinitionException('There is no type with name "'.$name.'"');
        }
        return $this->definitions[$name];
    }

    /**
     * {@inheritDoc}
     */
    function addType(TypeInterface $definition)
    {
        $names = (array)$definition->getName();
        foreach ($names as $name) {
            $this->definitions[$name] = $definition;
        }
        return $this;
    }
}