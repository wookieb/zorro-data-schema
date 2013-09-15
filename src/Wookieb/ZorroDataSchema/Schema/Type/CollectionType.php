<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\Assert\Assert;
use Wookieb\TypeCheck\TraversableOf;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * Collection type which is just a list of elements of defined type
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionType extends AbstractTypeCheckCachingType
{
    /**
     * @var TypeInterface
     */
    private $type;
    private $name;

    /**
     * @param string $name
     * @param TypeInterface $type
     * @throws \InvalidArgumentException when name is empty
     */
    public function __construct($name, TypeInterface $type)
    {
        Assert::notBlank($name, 'Name of collection cannot be empty');
        $this->name = $name;
        $this->setType($type);
    }

    /**
     * @return string
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
            if (!$this->type->isTargetType($value)) {
                $value = $this->type->create($value);
            }
            $collection[] = $value;
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
     * Sets type of data that collection contains
     *
     * @param TypeInterface $type
     * @return self
     */
    private function setType(TypeInterface $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
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
        if (!$this->isValidData($value)) {
            return false;
        }

        foreach ($value as $entry) {
            if (!$this->type->isTargetType($entry)) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function createTypeCheck()
    {
        return new TraversableOf($this->type->getTypeCheck());
    }
}
