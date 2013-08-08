<?php

namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * Definition of string type
 * Converts the following data to string:
 * - objects with __toString method implemented
 * - scalar values
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class StringType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        return $this->convert($data, 'create a string');
    }

    private function isValidData($value)
    {
        $isStringableObject = is_object($value) && method_exists($value, '__toString');
        $isScalar = is_scalar($value);
        return $isStringableObject || $isScalar;
    }

    private function convert($value, $to)
    {
        if (!$this->isValidData($value)) {
            $msg = 'Invalid data to '.$to.'. Only scalar values and objects with __toString allowed';
            throw new InvalidValueException($msg);
        }
        return (string)$value;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        return $this->convert($value, 'extract');
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_string($value);
    }
}