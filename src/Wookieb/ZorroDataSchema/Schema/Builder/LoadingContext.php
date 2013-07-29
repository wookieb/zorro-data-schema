<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder;
use Wookieb\ZorroDataSchema\Schema\Schema;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class LoadingContext
{
    private $resources = array();

    public function addResource($resource)
    {
        $this->resources[] = $resource;
        return $this;
    }

    public function getResources()
    {
        return $this->resources;
    }
}