<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\Builder\Loaders;
use Wookieb\ZorroDataSchema\SchemaOutline\Builder\LoadingContext;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaOutlineLoaderInterface
{
    function setLoadingContext(LoadingContext $context);
}
