<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Loaders;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\LoadingContext;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaLoaderInterface extends LoaderInterface
{
    function setLoadingContext(LoadingContext $context);
}