<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;
use Assert\Assertion;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class EnumOutline extends AbstractTypeOutline
{
    private $options = array();

    public function __construct($name, array $options)
    {
        parent::__construct($name);
        foreach ($options as $name => $value) {
            $this->addOption($name, $value);
        }
        if (count($this->options) < 2) {
            throw new \InvalidArgumentException('Enum require at least 2 options');
        }
    }

    /**
     * @param string $name
     * @param integer $value
     *
     * @return self
     */
    private function addOption($name, $value)
    {
        Assertion::notBlank('Option name cannot be empty');
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
}