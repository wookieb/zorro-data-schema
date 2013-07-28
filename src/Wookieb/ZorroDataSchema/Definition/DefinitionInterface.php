<?php
namespace Wookieb\ZorroDataSchema\Definition;
use Wookieb\ZorroDataSchema\Exception\NoDefaultValueException;
use Wookieb\ZorroDataSchema\Type\TypeInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface DefinitionInterface
{
    /**
     * Set type of definition
     *
     * @param TypeInterface $type
     * @return self
     */
    function setType(TypeInterface $type);

    /**
     * @return TypeInterface
     */
    function getType();

    /**
     * Indicate whether definition has default value
     * @return boolean
     */
    function hasDefaultValue();

    /**
     * Set default value of definition
     * Every call of this method makes this definition having default value
     *
     * @param mixed $value
     * @return self
     */
    function setDefaultValue($value);

    /**
     * Returns default value
     *
     * @return mixed
     * @throws NoDefaultValueException when definition does not have a default value
     */
    function getDefaultValue();

    /**
     * Removes default value of definition
     *
     * @return self
     */
    function removeDefaultValue();

    /**
     * {@see TypeInterface::create}
     * Use default value if $data is null
     *
     * @param $data
     * @return mixed
     */
    function create($data);

    /**
     * {@see TypeInterface::extract}
     * Use default is $value is null
     *
     * @param $value
     * @return mixed
     */
    function extract($value);

    /**
     * Returns info whether definition should accept null as possible value
     *
     * @return bool
     */
    function isNullable();

    /**
     * Set isNullable flag
     * Is isNullable is set on true then method automatically removes default value since it will never be used
     *
     * @param boolean $nullable
     * @return self
     */
    function setIsNullable($nullable);
}