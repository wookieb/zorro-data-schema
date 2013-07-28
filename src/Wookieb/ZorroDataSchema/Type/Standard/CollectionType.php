<?php

namespace Wookieb\ZorroDataSchema\Type\Standard;
use Wookieb\ZorroDataSchema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionType implements TypeInterface
{
    /**
     * @var TypeInterface
     */
    private $type;

    private $validationErrors = array();

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return array('collection', 'array');
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
     * {@inheritDoc}
     */
    public function isValid()
    {
        $this->validationErrors = array();
        if (!$this->type) {
            $this->validationErrors[] = 'No type';
        }
        return !(bool)$this->validationErrors;
    }

    /**
     * {@inheritDoc}
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
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