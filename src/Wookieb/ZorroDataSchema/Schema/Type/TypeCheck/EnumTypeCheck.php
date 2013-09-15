<?php

namespace Wookieb\ZorroDataSchema\Schema\Type\TypeCheck;
use Wookieb\TypeCheck\TypeCheckInterface;
use Wookieb\ZorroDataSchema\Schema\Type\EnumType;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class EnumTypeCheck implements TypeCheckInterface
{
    /**
     * @var \Wookieb\ZorroDataSchema\Schema\Type\EnumType
     */
    private $enum;

    public function __construct(EnumType $enum)
    {
        $this->enum = $enum;
    }

    /**
     * {@inheritDoc}
     */
    public function isValidType($data)
    {
        return is_int($data) && in_array($data, $this->enum->getOptions(), true);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeDescription()
    {
        $options = array();
        foreach ($this->enum->getOptions() as $optionName => $optionValue) {
            $options[] = $optionValue.' - '.$optionName;
        }
        return 'one of enum option ('.implode(', ', $options).')';
    }

} 
