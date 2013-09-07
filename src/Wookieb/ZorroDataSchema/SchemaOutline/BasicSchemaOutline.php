<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\CollectionDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\ChoiceDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BinaryOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ByteOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DateOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DoubleOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ExceptionOutline;
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
        $this->addTypeOutline(new ByteOutline());
        $this->addTypeOutline(new DateOutline());
        $this->addTypeOutline(new DoubleOutline());
        $this->addTypeOutline(new FloatOutline());
        $this->addTypeOutline(new StringOutline());

        $this->addTypeOutline(new Integer16Outline());
        $this->addTypeOutline(new Integer16Outline('int16'));
        $this->addTypeOutline(new Integer16Outline('short'));

        $this->addTypeOutline(new Integer32Outline());
        $this->addTypeOutline(new Integer32Outline('int32'));
        $this->addTypeOutline(new Integer32Outline('int'));
        $this->addTypeOutline(new Integer32Outline('integer'));

        $this->addTypeOutline(new Integer64Outline());
        $this->addTypeOutline(new Integer64Outline('int64'));
        $this->addTypeOutline(new Integer64Outline('long'));

        $this->addTypeOutline(new BooleanOutline());
        $this->addTypeOutline(new BooleanOutline('bool'));

        $this->addTypeOutline(new BinaryOutline());
        $this->addTypeOutline(new BinaryOutline('bin'));

        $this->addTypeOutline(new ExceptionOutline());

        $this->addDynamicTypeOutline(new CollectionDynamicTypeOutline($this));
        $this->addDynamicTypeOutline(new ChoiceDynamicTypeOutline($this));
    }
}
