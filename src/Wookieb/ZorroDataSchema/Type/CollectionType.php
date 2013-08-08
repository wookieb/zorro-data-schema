<?php

namespace Wookieb\ZorroDataSchema\Type;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Exception\InvalidTypeException;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;
use Wookieb\ZorroDataSchema\Type\TypeInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionType implements TypeInterface
{
    /**
     * @var TypeInterface
     */
    private $type;
    private $name;

    public function __construct($name)
    {
        Assertion::notBlank($name, 'Name of collection cannot be empty');
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if (!$this->isValidData($data)) {
            throw new InvalidValueException('Invalid data to create collection. Array and Traversable objects allowed');
        }
        $collection = array();
        foreach ($data as $value) {
            $collection[] = $this->type->create($value);
        }
        return $collection;
    }

    private function isValidData($data)
    {
        return is_array($data) || $data instanceof \Traversable;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isValidData($value)) {
            throw new InvalidValueException('Invalid data to extract. Array and Traversable objects allowed');
        }
        $collection = array();
        foreach ($value as $entry) {
            $collection[] = $this->type->extract($entry);
        }
        return $collection;
    }

    /**
     * Set type of data that collection contains
     *
     * @param TypeInterface $type
     * @return self
     */
    public function setType(TypeInterface $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Return type of data that collection contains
     *
     * @return TypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        if (!is_array($value) && !$value instanceof \Traversable) {
            return false;
        }

        foreach ($value as $entry) {
            if (!$this->type->isTargetType($entry)) {
                return false;
            }
        }
        return true;
    }
}