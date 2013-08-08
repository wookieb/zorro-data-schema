<?php
namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface TypeInterface
{
    /**
     * Creates local representation of given data
     *
     * @param mixed $data
     * @return mixed
     * @throws InvalidValueException
     */
    function create($data);

    /**
     * Creates global representation of given data
     *
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueException
     */
    function extract($value);

    /**
     * Check whether value has type of target type
     *
     * @param mixed $value
     * @return boolean
     */
    function isTargetType($value);
}