<?php

namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Type\AlwaysValidType;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BooleanType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return array('boolean', 'bool');
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        return (bool)$data;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        return (bool)$value;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_bool($value);
    }
}