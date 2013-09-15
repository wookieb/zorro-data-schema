<?php
namespace Wookieb\ZorroDataSchema\Schema\Type;

use Wookieb\TypeCheck\TypeCheckInterface;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface TypeInterface
{
    /**
     * Creates local representation of data
     *
     * @param mixed $data
     * @return mixed
     * @throws InvalidValueException
     */
    function create($data);

    /**
     * Creates global representation of data
     *
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueException
     */
    function extract($value);

    /**
     * Check whether value is a target type
     *
     * @param mixed $value
     * @return boolean
     */
    function isTargetType($value);

    /**
     * Returns a type check object
     *
     * @return TypeCheckInterface
     */
    function getTypeCheck();
}
