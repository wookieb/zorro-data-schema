<?php

namespace Wookieb\ZorroDataSchema\Schema;
use Wookieb\ZorroDataSchema\Schema\Type\BinaryType;
use Wookieb\ZorroDataSchema\Schema\Type\BooleanType;
use Wookieb\ZorroDataSchema\Schema\Type\DateType;
use Wookieb\ZorroDataSchema\Schema\Type\DoubleType;
use Wookieb\ZorroDataSchema\Schema\Type\FloatType;
use Wookieb\ZorroDataSchema\Schema\Type\IntegerType;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BasicSchema extends Schema
{
    public function __construct()
    {
        $this->registerType('byte', new IntegerType(8))
            ->registerType('date', new DateType())
            ->registerType('double', new DoubleType())
            ->registerType('float', new FloatType())
            ->registerType('string', new StringType())
            ->registerType('int16', new IntegerType(16))
            ->registerType('integer16', new IntegerType(16))
            ->registerType('short', new IntegerType(16))
            ->registerType('int32', new IntegerType(32))
            ->registerType('integer32', new IntegerType(32))
            ->registerType('int', new IntegerType(32))
            ->registerType('integer', new IntegerType(32))
            ->registerType('int64', new IntegerType())
            ->registerType('integer64', new IntegerType())
            ->registerType('long', new IntegerType())
            ->registerType('boolean', new BooleanType())
            ->registerType('bool', new BooleanType())
            ->registerType('binary', new BinaryType())
            ->registerType('bin', new BinaryType());
    }
} 
