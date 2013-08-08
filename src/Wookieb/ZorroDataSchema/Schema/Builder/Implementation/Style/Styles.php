<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style;
use Assert\Assertion;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class Styles
{
    private $styles = array();

    /**
     * Register new style
     *
     * @param string $name style name
     * @param StyleInterface $style
     * @return self
     */
    public function registerStyle($name, StyleInterface $style)
    {
        Assertion::notBlank($name, 'Name of style cannot be empty');
        $this->styles[$name] = $style;
        return $this;
    }

    /**
     * @param string $name
     * @return StyleInterface
     * @throws \OutOfRangeException
     */
    public function getStyle($name)
    {
        if (!$this->hasStyle($name)) {
            throw new \OutOfRangeException('Style with name "'.$name.'" does not exists');
        }
        return $this->styles[$name];
    }

    /**
     * Check whether style with given name exists
     *
     * @param string $name
     * @return boolean
     */
    public function hasStyle($name)
    {
        return isset($this->styles[$name]);
    }
}