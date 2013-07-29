<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Loaders;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\FileLoader;
use Wookieb\ZorroDataSchema\Schema\Builder\LoadingContext;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class BaseFileLoader extends FileLoader implements SchemaLoaderInterface
{
    /**
     * @var LoadingContext
     */
    protected $loadingContext;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @var NodeInterface
     */
    protected $configDefinition;

    public function setLoadingContext(LoadingContext $context)
    {
        $this->loadingContext = $context;
    }
}