<?php

namespace Wookieb\ZorroDataSchema\Loader;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface ZorroLoaderInterface extends LoaderInterface
{
    function setLoadingContext(LoadingContext $context);
}
