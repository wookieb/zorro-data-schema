<?php

namespace Wookieb\ZorroDataSchema\Loader;
use Symfony\Component\Config\Loader\FileLoader;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
abstract class BaseFileLoader extends FileLoader implements ZorroLoaderInterface
{
    /**
     * @var \Wookieb\ZorroDataSchema\Loader\LoadingContext
     */
    protected $loadingContext;

    public function setLoadingContext(LoadingContext $context)
    {
        $this->loadingContext = $context;
    }
}
