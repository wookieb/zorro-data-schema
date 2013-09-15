<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;

use Wookieb\Map\MapEntry;
use Wookieb\Map\MapInterface;
use Wookieb\Map\StrictMap;
use Wookieb\Map\StrictMapTypeCheck;
use Wookieb\TypeCheck\TypeCheckInterface;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class MapType extends AbstractTypeCheckCachingType
{
    /**
     * @var TypeInterface
     */
    private $keyType;
    /**
     * @var TypeInterface
     */
    private $valueType;

    public function __construct(TypeInterface $keyType, TypeInterface $valueType)
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    /**
     * {@inheritDoc}
     */
    public function create($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new InvalidValueException('Invalid data to create a map. Only traversable structures allowed');
        }

        $map = new StrictMap($this->keyType->getTypeCheck(), $this->valueType->getTypeCheck());
        foreach ($data as $entry) {
            if (is_object($entry)) {
                $entry = (array)$entry;
            } else if (!is_array($entry)) {
                throw new InvalidValueException('Invalid data to create a map. Each entry must be an array or object');
            }
            if (!array_key_exists('key', $entry)) {
                throw new InvalidValueException('Invalid data to create a map. One of entry does not contain "key" key');
            }
            if (!array_key_exists('value', $entry)) {
                throw new InvalidValueException('Invalid data to create a map. One of entry does not contain "value" key');
            }

            try {
                $key = $this->keyType->create($entry['key']);
            } catch (\Exception $e) {
                throw new InvalidValueException('One of entry of map contains invalid "key"', 0, $e);
            }

            try {
                $value = $this->valueType->create($entry['value']);
            } catch (\Exception $e) {
                throw new InvalidValueException('One of entry of map contains invalid "value"', 0, $e);
            }

            $map->add($key, $value);
        }
        return $map;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isTargetType($value)) {
            $msg = vsprintf('Invalid data to extract. Only %s', array(
                $this->getTypeCheck()->getTypeDescription()
            ));
            throw new InvalidValueException($msg);
        }

        /* @var MapInterface $value */
        $value->rewind();
        $result = array();
        while ($value->valid()) {
            $entryKey = $value->key();
            $entryValue = $value->current();

            if ($value->isUsingMapEntries()) {
                /* @var MapEntry $entryValue */
                list($entryKey, $entryValue) = $entryValue->get();
            }
            $result[] = array(
                'key' => $this->keyType->extract($entryKey),
                'value' => $this->valueType->extract($entryValue)
            );
            $value->next();
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return $this->getTypeCheck()->isValidType($value);
    }

    /**
     * @return TypeCheckInterface
     */
    protected function createTypeCheck()
    {
        return new StrictMapTypeCheck($this->keyType->getTypeCheck(), $this->valueType->getTypeCheck());
    }
} 
