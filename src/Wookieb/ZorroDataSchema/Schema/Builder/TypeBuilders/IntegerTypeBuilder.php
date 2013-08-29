<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Schema\Type\IntegerType;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class IntegerTypeBuilder implements TypeBuilderInterface
{
    private static $map = array(
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ByteOutline' => 8,
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer16Outline' => 16,
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer32Outline' => 32,
        'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer64Outline' => 64
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
        $numOfBites = @self::$map[get_class($typeOutline)];
        if (!$numOfBites) {
            throw new UnableToGenerateTypeException('Cannot match num of bites', $typeOutline);
        }
        return new IntegerType($numOfBites);
    }

}
