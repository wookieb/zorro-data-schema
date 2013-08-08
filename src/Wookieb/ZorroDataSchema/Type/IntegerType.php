<?php

namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;
use Wookieb\ZorroDataSchema\Type\AlwaysValidType;


/**
 * Type to represent integers
 * // TODO sentence
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class IntegerType implements TypeInterface
{

    private $numOfBites = 32;

    private static $maxValues = array(
        8 => 127,
        16 => 32767,
        32 => 2147483647
    );

    public function __construct($numOfBites = 32)
    {
        $allowedValues = array(8, 16, 32, 64);
        if (!in_array($numOfBites, $allowedValues, true)) {
            $msg = 'Invalid num of bites for integer. Only '.implode(', ', $allowedValues).' allowed';
            throw new \InvalidArgumentException($msg);
        }
        $this->numOfBites = $numOfBites;
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if (!$this->isValidData($data)) {
            throw new InvalidValueException('Invalid data to create an integer. Only scalar data allowed');
        }
        return $this->closeInRange($data);
    }

    private function is64Bit()
    {
        $int = "9223372036854775807";
        $int = intval($int);
        return $int === 9223372036854775807;
    }

    private function closeInRange($value)
    {
        $value = (int)$value;
        if ($this->numOfBites < 64) {
            $maxValue = self::$maxValues[$this->numOfBites];
            if ($value > $maxValue) {
                return $maxValue;
            }
        }
        return $value;
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
        return $this->closeInRange($value);
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_int($value);
    }
}