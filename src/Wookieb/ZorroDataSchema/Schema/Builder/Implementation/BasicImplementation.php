<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BasicImplementation extends Implementation
{
    public function __construct(ClassMapInterface $classMap = null, GlobalClassTypeImplementation $globalOptions = null)
    {
        parent::__construct($classMap, $globalOptions);
        $this->useExceptionImplementation();
    }

    private function useExceptionImplementation()
    {
        $this->getClassMap()->registerClass('Exception', '\Exception');

        $implementation = new ClassTypeImplementation('Exception');
        $causePropertyImplementation = new PropertyImplementation('cause');
        $causePropertyImplementation->setTargetPropertyName('previous');
        $implementation->addPropertyImplementation($causePropertyImplementation);

        $this->registerClassTypeImplementation($implementation);
    }
} 
