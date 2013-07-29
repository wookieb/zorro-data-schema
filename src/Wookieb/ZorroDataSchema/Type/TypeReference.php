<?php

namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class TypeReference implements TypeInterface
{
    private $name;
    private $type;

    /**
     * @param string $name
     * @param TypeInterface $type
     */
    public function __construct($name, TypeInterface $type)
    {
        $this->name = $name;
        $this->type = $type;
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
    public function getAliases()
    {
        return array();
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        $this->type->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        $this->type->extract($value);
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return $this->type->isTargetType($value);
    }
}