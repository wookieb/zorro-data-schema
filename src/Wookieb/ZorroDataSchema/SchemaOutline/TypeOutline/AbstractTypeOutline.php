<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;
use Wookieb\Assert\Assert;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class AbstractTypeOutline implements TypeOutlineInterface
{
    private $name;
    protected $defaultName;

    /**
     * Set current name as provided in argument
     * If name is null then default name (from defaultName property) will be set
     *
     * @param null|string $name
     *
     * @throws \InvalidArgumentException when name is invalid
     */
    public function __construct($name = null)
    {
        Assert::nullOrNotBlank($name, 'Name of type outline cannot be empty');
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
