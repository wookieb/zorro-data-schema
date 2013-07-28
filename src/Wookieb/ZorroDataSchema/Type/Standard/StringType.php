<?php

namespace Wookieb\ZorroDataSchema\Type\Standard;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class StringType extends AlwaysValidType
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'string';
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if (!$this->isValidData($data)) {
            $msg = 'Invalid data to create a string. Only scalar values and objects with __toString allowed';
            throw new InvalidValueException($msg);
        }
        return (string)$data;
    }

    private function isValidData($value)
    {
        $isStringableObject = is_object($value) && method_exists($value, '__toString');
        $isScalar = is_scalar($value);
        return $isStringableObject || $isScalar;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isValidData($value)) {
            $msg = 'Invalid value to extract. Only scalar values and objects with __toString allowed';
            throw new InvalidValueException($msg);
        }
        return (string)$value;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_string($value);
    }
}