<?php

namespace Wookieb\ZorroDataSchema\Type\Standard;
use Wookieb\ZorroDataSchema\Type\TypeInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class AlwaysValidType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getValidationErrors()
    {
        return array();
    }
}