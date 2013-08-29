<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StandardStyles;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class Implementation implements ImplementationInterface
{
    private $classImplementations = array();
    /**
     * @var GlobalClassTypeImplementation
     */
    private $globalClassOptions;
    /**
     * @var ClassMapInterface
     */
    private $classMap;

    public function __construct(ClassMapInterface $classMap = null, GlobalClassTypeImplementation $globalOptions = null)
    {
        $this->setGlobalClassTypeImplementation($globalOptions ? : new GlobalClassTypeImplementation());
        $this->setClassMap($classMap ? : new ClassMap());
    }

    public function getClassTypeImplementation($name)
    {
        if (isset($this->classImplementations[$name])) {
            $classOptions = $this->classImplementations[$name];
        } else {
            $classOptions = new ClassTypeImplementation($name);
            if ($this->globalClassOptions->getAccessorsStyle()) {
                $classOptions->setAccessorsStyle($this->globalClassOptions->getAccessorsStyle());
            }
            $classOptions->setAccessorsEnabled($this->globalClassOptions->isAccessorsEnabled());
        }

        if (!$classOptions->getClassName()) {
            $classOptions->setClassName($this->classMap->getClass($name));
        }
        return $classOptions;
    }

    public function registerClassTypeImplementation(ClassTypeImplementation $classImplementation)
    {
        $this->classImplementations[$classImplementation->getName()] = $classImplementation;
        return $this;
    }

    public function setGlobalClassTypeImplementation(GlobalClassTypeImplementation $options)
    {
        $this->globalClassOptions = $options;
        return $this;
    }

    public function setClassMap(ClassMapInterface $classMap)
    {
        $this->classMap = $classMap;
        return $this;
    }

    /**
     * @return GlobalClassTypeImplementation
     */
    public function getGlobalClassImplementation()
    {
        return $this->globalClassOptions;
    }

    /**
     * @return ClassMapInterface
     */
    public function getClassMap()
    {
        return $this->classMap;
    }
}
