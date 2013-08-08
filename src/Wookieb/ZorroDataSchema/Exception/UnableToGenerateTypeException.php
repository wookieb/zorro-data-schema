<?php

namespace Wookieb\ZorroDataSchema\Exception;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class UnableToGenerateTypeException extends ZorroDataSchemaException
{
    private $typeOutline;

    public function __construct($message, TypeOutlineInterface $typeOutline, \Exception $previous = null)
    {
        parent::__construct($message, null, $previous);
        $this->typeOutline = $typeOutline;
    }

    public function getTypeOutline()
    {
        return $this->typeOutline;
    }
}