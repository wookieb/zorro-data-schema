<?php

namespace Wookieb\Tests\Resources;

/**
 * http://strangenamesasaservice.nodejitsu.com/ <3
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class PointedZebra
{
    private $mundaneInsurance;
    protected $knobbyXylophone;
    public $putridValue;

    public function setMundaneInsurance($value)
    {
        $this->mundaneInsurance = $value.' - changed by setter';
    }

    public function getMundaneInsurance()
    {
        return $this->mundaneInsurance.' - changed by getter';
    }
}