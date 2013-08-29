<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;
use Wookieb\Assert\Assert;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class EnumOutline extends AbstractTypeOutline
{
    private $options = array();

    /**
     * @param null|string $name
     * @param array $options
     *
     * @throws \InvalidArgumentException when name is invalid or when enum does not contains at least 2 options
     */
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

    private function addOption($name, $value)
    {
        Assert::notBlank($name, 'Option name cannot be empty');
        $value = (int)$value;
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * Returns list of options of enum
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
