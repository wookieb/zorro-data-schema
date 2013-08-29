<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StyleInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class GlobalClassTypeImplementation
{
    /**
     * @var StyleInterface
     */
    protected $style;
    protected $accessorsEnabled = false;

    /**
     * Sets style of names of accessors
     *
     * @param StyleInterface $style
     * @return $this
     */
    public function setAccessorsStyle(StyleInterface $style = null)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Returns generator of names of accessors
     *
     * @return StyleInterface
     */
    public function getAccessorsStyle()
    {
        return $this->style;
    }

    /**
     * Enables/disables usage of accessors
     *
     * @param boolean $state
     * @return self
     */
    public function setAccessorsEnabled($state)
    {
        $this->accessorsEnabled = (bool)$state;
        return $this;
    }

    /**
     * Returns information whether usage of accessors is allowed
     *
     * @return boolean
     */
    public function isAccessorsEnabled()
    {
        return $this->accessorsEnabled;
    }
}
