<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Assert\Assertion;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StyleInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class GlobalClassOptions
{
    private $style;
    private $useSetters = false;

    public function setSettersAndGettersStyle(StyleInterface $style = null)
    {
        $this->style = $style;
        return $this;
    }

    public function getSettersAndGettersStyle()
    {
        return $this->style;
    }

    public function setUseSetters($useSetters)
    {
        $this->useSetters = (bool)$useSetters;
        return $this;
    }

    public function getUseSetters()
    {
        return $this->useSetters;
    }
}