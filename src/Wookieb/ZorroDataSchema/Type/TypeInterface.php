<?php
namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface TypeInterface
{
    /**
     * Returns type name
     *
     * @return string
     */
    function getName();

    /**
     * Returns aliases for this type
     *
     * @return array
     */
    function getAliases();

    /**
     * Create value from data
     *
     * @param mixed $data
     * @return mixed
     * @throws InvalidValueException
     */
    function create($data);

    /**
     * Extract data from value
     *
     * @param mixed $value
     * @return mixed
     * @throws InvalidValueException
     */
    function extract($value);

    /**
     * Check whether value is type of target type
     *
     * @param mixed $value
     * @return boolean
     */
    function isTargetType($value);
}