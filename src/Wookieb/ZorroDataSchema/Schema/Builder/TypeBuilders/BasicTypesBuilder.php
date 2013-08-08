<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BasicTypesBuilder implements TypeBuilderInterface
{
    private static $map = array(
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BinaryOutline' => 'Wookieb\ZorroDataSchema\Type\BinaryType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline' => 'Wookieb\ZorroDataSchema\Type\BooleanType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DateOutline' => 'Wookieb\ZorroDataSchema\Type\DateType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DoubleOutline' => 'Wookieb\ZorroDataSchema\Type\DoubleType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\FloatOutline' => 'Wookieb\ZorroDataSchema\Type\FloatType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline' => 'Wookieb\ZorroDataSchema\Type\StringType'
    );

    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate(TypeOutlineInterface $typeOutline)
    {
        return isset(self::$map[get_class($typeOutline)]);
    }

    /**
     * {@inheritDoc}
     */
    public function generate(TypeOutlineInterface $typeOutline, $implementation = 'php')
    {
        $typeClass = @self::$map[get_class($typeOutline)];
        if (!$typeClass) {
            $msg = 'No basic type class defined for "'.$typeOutline->getName().'" type outline';
            throw new UnableToGenerateTypeException($msg, $typeOutline);
        }
        return new $typeClass;
    }
}