<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class FloatType implements TypeInterface
{

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if (!$this->isValidData($data)) {
            throw new InvalidValueException('Invalid data to create a float. Only scalar values allowed');
        }
        return (float)$data;
    }

    private function isValidData($value)
    {
        return is_scalar($value) || $value === null;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isValidData($value)) {
            throw new InvalidValueException('Invalid value to extract. Only scalar values allowed');
        }
        return (float)$value;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_float($value);
    }
}
