<?php

namespace Wookieb\ZorroDataSchema\Type;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Exception\InvalidTypeException;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class EnumType implements TypeInterface
{
    private $name;
    private $options = array();

    public function __construct($name, array $options)
    {
        Assertion::notBlank('Name of enum cannot be empty');
        $this->name = $name;

        foreach ($options as $optionName => $optionValue) {
            $this->addOption($optionName, $optionValue);
        }
        if (count($this->options) <= 1) {
            throw new InvalidTypeException(array('Enum must contain at least 2 options'));
        }
    }

    public function addOption($name, $value)
    {
        Assertion::notBlank($name);
        $value = (int)$value;
        $this->options[$name] = $value;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        return $this->convert($data);
    }

    private function convert($value)
    {
        if (!is_scalar($value)) {
            throw new InvalidValueException('Invalid data to create a enum value. Only scalar values allowed');
        }

        if (isset($this->options[$value])) {
            return $this->options[$value];
        }

        $value = (int)$value;
        if (in_array($value, $this->options, true)) {
            return $value;
        }

        throw new InvalidValueException('Provided enum value '.$value.' is unavailable');
    }

    /**
     * Extract data from value
     *
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueException
     */
    public function extract($value)
    {
        return $this->convert($value);
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return is_int($value);
    }
}