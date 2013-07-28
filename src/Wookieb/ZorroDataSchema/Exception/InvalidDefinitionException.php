<?php
namespace Wookieb\ZorroDataSchema\Exception;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class InvalidDefinitionException extends ZorroDataSchemaException
{
    private $errors = array();

    public function __construct(array $errors, $message = 'Invalid definition')
    {
        $this->errors = $errors;
        parent::__construct($message);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}