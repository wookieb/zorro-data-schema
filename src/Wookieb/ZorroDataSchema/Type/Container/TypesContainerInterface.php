<?php
namespace Wookieb\ZorroDataSchema\Type\Container;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\NoSuchDefinitionException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface TypesContainerInterface extends \IteratorAggregate
{
    /**
     * Returns definition with given name
     *
     * @param string $name
     * @return TypeInterface
     * @throws NoSuchDefinitionException when no definition found
     */
    function getType($name);

    /**
     * Add new definition to pool
     *
     * @param TypeInterface $definition
     * @return self
     */
    function addType(TypeInterface $definition);
}