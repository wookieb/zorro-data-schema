<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class MapToSpecialTypesBuilder implements TypeBuilderInterface
{
    private $map = array();


    public function __construct()
    {
        $this->mapToSpecialType('Exception', 'Wookieb\ZorroDataSchema\Type\ExceptionType');
    }

    public function mapToSpecialType($typeOutlineName, $targetTypeClass)
    {
        Assertion::notBlank($typeOutlineName, 'Type outline name to map cannot be empty');
        $this->map[$typeOutlineName] = $targetTypeClass;
        return $this;
    }

    public function isAbleToGenerate(TypeOutlineInterface $typeOutline)
    {
        return isset($this->map[$typeOutline->getName()]);
    }

    public function generate(TypeOutlineInterface $typeOutline, $implementation = 'php')
    {
        if (!$this->isAbleToGenerate($typeOutline)) {
            $msg = 'There is no mapped type class for type "'.$typeOutline->getName().'"';
            throw new UnableToGenerateTypeException($msg, $typeOutline);
        }

        $className = $this->map[$typeOutline->getName()];
        return new $className;
    }
}