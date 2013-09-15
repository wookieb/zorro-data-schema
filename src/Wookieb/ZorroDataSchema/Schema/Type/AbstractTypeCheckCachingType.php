<?php

namespace Wookieb\ZorroDataSchema\Schema\Type;
use Wookieb\TypeCheck\TypeCheckInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class AbstractTypeCheckCachingType implements TypeInterface
{
    /**
     * @var TypeCheckInterface
     */
    private $typeCheck;

    /**
     * @return TypeCheckInterface
     */
    protected abstract function createTypeCheck();

    /**
     * {@inheritDoc}
     */
    public function getTypeCheck()
    {
        if (!$this->typeCheck) {
            $this->typeCheck = $this->createTypeCheck();
        }
        return $this->typeCheck;
    }
} 
