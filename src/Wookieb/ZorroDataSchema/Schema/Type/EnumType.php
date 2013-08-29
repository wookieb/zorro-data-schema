<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\Assert\Assert;
use Wookieb\ZorroDataSchema\Exception\InvalidTypeException;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class EnumType implements TypeInterface
{
    private $name;
    private $options = array();

    /**
     * @param string $name
     * @param array $options
     * @throws \InvalidArgumentException when name is invalid
     * @throws InvalidTypeException when enum does not contain at least 2 options
     */
    public function __construct($name, array $options)
    {
        Assert::notBlank($name, 'Name of enum cannot be empty');
        $this->name = $name;

        foreach ($options as $optionName => $optionValue) {
            $this->addOption($optionName, $optionValue);
        }
        if (count($this->options) <= 1) {
            throw new InvalidTypeException(array('Enum must contain at least 2 options'));
        }
    }

    /**
     * Add new enum option
     *
     * @param string $name
     * @param integer $value
     * @return self
     * @throws \InvalidArgumentException when option name is empty
     */
    public function addOption($name, $value)
    {
        Assert::notBlank($name, 'Option name cannot be empty');
        $value = (int)$value;
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Returns option value for given value
     *
     * @param mixed $value name or value of option
     * @return integer
     * @throws InvalidValueException
     */
    public function create($value)
    {
        if (!is_scalar($value)) {
            throw new InvalidValueException('Invalid data to create enum value. Only scalar values allowed');
        }

        if (isset($this->options[$value])) {
            return $this->options[$value];
        }

        $value = (int)$value;
        if ($this->isValidValue($value)) {
            return $value;
        }

        throw new InvalidValueException('Option or value "'.$value.'" does not exist');
    }

    private function isValidValue($value)
    {
        return in_array($value, $this->options, true);
    }

    /**
     * Returns name of option for given value
     *
     * @param mixed $value name or value of option
     * @return string
     * @throws InvalidValueException
     */
    public function extract($value)
    {
        if (!is_scalar($value)) {
            throw new InvalidValueException('Invalid data to extract. Only scalar values allowed');
        }

        if (isset($this->options[$value])) {
            return $value;
        }

        $value = (int)$value;
        $found = array_search($value, $this->options, true);
        if ($found) {
            return $found;
        }
        throw new InvalidValueException('Option or value "'.$value.'" does not exist');
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_int($value) && $this->isValidValue($value);
    }
}
