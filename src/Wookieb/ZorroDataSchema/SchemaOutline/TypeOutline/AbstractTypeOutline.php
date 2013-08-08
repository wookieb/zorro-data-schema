<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;
use Assert\Assertion;


/**
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class AbstractTypeOutline implements TypeOutlineInterface
{
    private $name;

    protected $defaultName;

    public function __construct($name = null)
    {
        if ($this->defaultName === null) {
            Assertion::notBlank($name);
        } else {
            Assertion::nullOrnotBlank($name);
        }
        $this->name = $name ? : $this->defaultName;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
}