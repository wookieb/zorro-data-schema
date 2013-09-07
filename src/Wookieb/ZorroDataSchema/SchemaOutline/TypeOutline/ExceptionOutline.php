<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ExceptionOutline extends ClassOutline
{
    public function __construct()
    {
        parent::__construct('Exception');
        $this->addProperty(new PropertyOutline('message', new StringOutline()));
        $this->addProperty(new PropertyOutline('cause', $this, true));
    }
} 
