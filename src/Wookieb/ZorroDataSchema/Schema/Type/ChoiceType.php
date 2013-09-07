<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\Assert\Assert;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ChoiceType implements TypeInterface
{
    private $types = array();

    /**
     * @param string $name
     * @return TypeInterface
     */
    protected function getTypeByName($name)
    {
        if (isset($this->types[$name])) {
            return $this->types[$name];
        }
    }

    /**
     * @param mixed $data
     * @return array
     */
    protected function getTypeAbleToExtractData($data)
    {
        foreach ($this->types as $typeName => $type) {
            /** @var TypeInterface $type */
            if ($type->isTargetType($data)) {
                return array($typeName, $type);
            }
        }
    }

    /**
     * @param string $name
     * @param TypeInterface $type
     * @return self
     * @throws \InvalidArgumentException when name is empty
     */
    public function addType($name, TypeInterface $type)
    {
        Assert::notBlank($name, 'Name of type cannot be empty');
        $this->types[$name] = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return (bool)$this->getTypeAbleToExtractData($value);
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if ($data instanceof \stdClass) {
            $data = (array)$data;
        }
        if (!is_array($data)) {
            throw new InvalidValueException('Invalid data to create object. Only array and stdClass allowed');
        }

        if (!isset($data['__type'])) {
            throw new InvalidValueException('Invalid array structure. No "__type" defined');
        }

        if (!isset($data['data'])) {
            throw new InvalidValueException('Invalid array structure. No "data" defined');
        }

        $typeName = $data['__type'];
        $type = $this->getTypeByName($typeName);
        if (!$type) {
            $msg = 'Cannot handle value of type "'.$typeName.'" since there is no registered type with that name';
            throw new InvalidValueException($msg);
        }

        return $type->create($data['data']);
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        list($typeName, $type) = $this->getTypeAbleToExtractData($value);
        if (!$type) {
            $msg = 'No type found to handle ';
            if (is_object($value)) {
                $msg .= 'object of class "'.get_class($value).'"';
            } else {
                $msg .= gettype($value);
            }
            throw new InvalidValueException($msg);
        }

        return array(
            '__type' => $typeName,
            'data' => $type->extract($value)
        );
    }
}
