<?php

namespace Wookieb\ZorroDataSchema\Loader;
use Symfony\Component\Config\Resource\ResourceInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class LoadingContext
{
    private $resources = array();

    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
        return $this;
    }

    public function getResources()
    {
        return $this->resources;
    }
}