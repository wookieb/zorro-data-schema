<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * Date type which represents date as \DateTime object
 *
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
            throw new InvalidValueException('Invalid format of data to create a Date', null, $e);
        }
        throw new InvalidValueException('Invalid data to create a Date');
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$value instanceof \DateTime) {
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