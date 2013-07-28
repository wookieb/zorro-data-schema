<?php

namespace Wookieb\ZorroDataSchema\Type\Standard;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class IntegerType extends AlwaysValidType
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return array('integer', 'int');
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if (!$this->isValidData($data)) {
            throw new InvalidValueException('Invalid data to create an integer. Only scalar data allowed');
        }
        return (int)$data;
    }

    private function isValidData($value)
    {
        return is_scalar($value);
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isValidData($value)) {
            throw new InvalidValueException('Invalid value to extract. Only scalar values allowed');
        }
        return (int)$value;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_int($value);
    }


}