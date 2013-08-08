<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\Styles;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class Implementation implements ImplementationInterface
{

    private $options = array();
    /**
     * @var GlobalClassOptions
     */
    private $globalClassOptions;

    private $styles;
    /**
     * @var ClassMapInterface
     */
    private $classMap;

    public function __construct(ClassMap $classMap = null, GlobalClassOptions $globalOptions = null, Styles $styles = null)
    {
        if ($styles) {
            $this->setStyles($styles);
        }

        if ($classMap) {
            $this->setClassMap($classMap);
        }

        $this->setGlobalClassOptions($globalOptions ? : new GlobalClassOptions());
    }

    public function getClassOptions($name)
    {
        if (isset($this->options[$name])) {
            $classOptions = clone $this->options[$name];
        } else {
            $classOptions = new ClassOptions();
            $classOptions->setSettersAndGettersStyle($this->globalClassOptions->getSettersAndGettersStyle());
            $classOptions->setUseSetters($this->globalClassOptions->getUseSetters());
        }

        if (!$classOptions->getClassName()) {
            $classOptions->setClassName($this->classMap->getClass($name));
        }
        return $classOptions;
    }

    public function setClassOptions($name, ClassOptions $classOptions)
    {
        $this->options[$name] = $classOptions;
        return $this;
    }

    public function setGlobalClassOptions(GlobalClassOptions $options)
    {
        $this->globalClassOptions = $options;
        return $this;
    }

    public function setStyles(Styles $styles)
    {
        $this->styles = $styles;
        return $this;
    }

    public function setClassMap(ClassMapInterface $classMap)
    {
        $this->classMap = $classMap;
        return $this;
    }
}