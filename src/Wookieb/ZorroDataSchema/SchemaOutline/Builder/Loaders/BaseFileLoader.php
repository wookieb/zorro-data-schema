<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\Builder\Loaders;
use Symfony\Component\Config\Loader\FileLoader;
use Wookieb\ZorroDataSchema\SchemaOutline\Builder\Loaders\SchemaOutlineLoaderInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\Builder\LoadingContext;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class BaseFileLoader extends FileLoader implements SchemaOutlineLoaderInterface
{
    /**
     * @var LoadingContext
     */
    protected $loadingContext;

    public function setLoadingContext(LoadingContext $context)
    {
        $this->loadingContext = $context;
    }
}