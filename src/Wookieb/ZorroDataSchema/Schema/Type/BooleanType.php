<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\TypeCheck\TypeCheck;


/**
 * Converts data to boolean value
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BooleanType implements TypeInterface
{
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

    /**
     * {@inheritDoc}
     */
    public function getTypeCheck()
    {
        return TypeCheck::booleans();
    }
}
