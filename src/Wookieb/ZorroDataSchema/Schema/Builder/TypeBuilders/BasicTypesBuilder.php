<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;

/**
 * Maps basic types outline to types
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BasicTypesBuilder implements TypeBuilderInterface
{
    private static $map = array(
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BinaryOutline' => 'Wookieb\ZorroDataSchema\Schema\Type\BinaryType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline' => 'Wookieb\ZorroDataSchema\Schema\Type\BooleanType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DateOutline' => 'Wookieb\ZorroDataSchema\Schema\Type\DateType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\DoubleOutline' => 'Wookieb\ZorroDataSchema\Schema\Type\DoubleType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\FloatOutline' => 'Wookieb\ZorroDataSchema\Schema\Type\FloatType',
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline' => 'Wookieb\ZorroDataSchema\Schema\Type\StringType'
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
    public function generate(TypeOutlineInterface $typeOutline, ImplementationInterface $implementation)
    {
        $typeOutlineClass = get_class($typeOutline);
        if (!isset(self::$map[$typeOutlineClass])) {
            $msg = 'No basic type defined for "'.$typeOutline->getName().'" type outline';
            throw new UnableToGenerateTypeException($msg, $typeOutline);
        }
        return new self::$map[$typeOutlineClass];
    }
}
