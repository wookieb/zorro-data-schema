<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\TypeCheck\TypeCheck;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * Definition of string type
 * Converts the following data to string:
 * - objects with __toString method
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
        $hasToString = is_object($value) && method_exists($value, '__toString');
        $isScalar = is_scalar($value) || $value === null;
        return $hasToString || $isScalar;
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
     * @inheritdoc
     */
    public function isTargetType($value)
    {
        return is_string($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeCheck()
    {
        return TypeCheck::strings();
    }
}
