<?php

namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class DateType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if ($data instanceof \DateTime) {
            return $data;
        }
        try {
            if (is_numeric($data)) {
                return \DateTime::createFromFormat('U', $data);
            }
            if (is_string($data)) {
                return new \DateTime($data);
            }
        } catch (\Exception $e) {
            throw new InvalidValueException('Invalid format of data to create Date', null, $e);
        }
        throw new InvalidValueException('Invalid data to create Date');
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if ($value instanceof \DateTime) {
            throw new InvalidValueException('Invalid value to extract. Only DateTime objects allowed');
        }
        return $value->format(\DateTime::ISO8601);
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return $value instanceof \DateTime;
    }
}