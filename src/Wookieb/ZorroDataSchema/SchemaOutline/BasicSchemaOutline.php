<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\CollectionDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BinaryOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ByteOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DateOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DoubleOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\FloatOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer16Outline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer32Outline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer64Outline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;


/**
 * Schema outline with predefined list of basic types
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BasicSchemaOutline extends SchemaOutline
{
    public function __construct()
    {
        $this->addType(new ByteOutline());
        $this->addType(new DateOutline());
        $this->addType(new DoubleOutline());
        $this->addType(new FloatOutline());
        $this->addType(new StringOutline());

        $this->addType(new Integer16Outline());
        $this->addType(new Integer32Outline('int16'));

        $this->addType(new Integer32Outline());
        $this->addType(new Integer32Outline('int32'));

        $this->addType(new Integer64Outline());
        $this->addType(new Integer64Outline('int64'));

        $this->addType(new BooleanOutline());
        $this->addType(new BooleanOutline('bool'));

        $this->addType(new BinaryOutline());
        $this->addType(new BinaryOutline('bin'));

        $this->addDynamicType(new CollectionDynamicTypeOutline($this));
    }
}