<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;

use Wookieb\TypeCheck\CallbackTypeCheck;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * Type to represent integers and "byte"
 * Cuts range of possible values to make them fit in, defined in constructor, num of bites
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class IntegerType extends AbstractTypeCheckCachingType
{
    private $numOfBites = 64;

    private static $maxValues = array(
        8 => 127,
        16 => 32767,
        32 => 2147483647,
        64 => PHP_INT_MAX
    );

    /**
     * @param integer $numOfBites allowed values 8, 16, 32, 64
     * @throws \InvalidArgumentException when amount of bites is invalid
     */
    public function __construct($numOfBites = 64)
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

    private function closeInRange($value)
    {
        $value = (int)$value;
        if ($this->numOfBites < 64) {
            $maxValue = self::$maxValues[$this->numOfBites];
            if ($value > $maxValue) {
                $value = $maxValue;
            }
        }
        return $value;
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
        return $this->closeInRange($value);
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return $this->getTypeCheck()->isValidType($value);
    }

    /**
     * Returns max value of integers accepted by this type
     *
     * @return integer
     */
    public function getMaxValue()
    {
        return self::$maxValues[$this->numOfBites];
    }

    /**
     * {@inheritDoc}
     */
    protected function createTypeCheck()
    {
        $maxValue = $this->getMaxValue();
        $numOfBites = $this->numOfBites;
        return new CallbackTypeCheck(function ($value) use ($maxValue, $numOfBites) {
            return is_int($value) && ($numOfBites >= 64 || ($value <= $maxValue && $value >= -$maxValue - 1));
        }, 'integers in range '.(-$maxValue - 1).' to '.$maxValue);
    }
}
