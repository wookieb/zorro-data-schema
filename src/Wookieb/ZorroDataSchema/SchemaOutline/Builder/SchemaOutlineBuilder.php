<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\Builder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Wookieb\ZorroDataSchema\Exception\SchemaOutlineLoadingException;
use Wookieb\ZorroDataSchema\Exception\ZorroDataSchemaException;
use Wookieb\ZorroDataSchema\Loader\LoadingContext;
use Wookieb\ZorroDataSchema\Loader\ZorroLoaderInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\BasicSchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\HoistClassDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\EnumOutline;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaOutlineBuilder
{
    protected $baseSchema;
    protected $loadingContext;
    private $resolver;
    private $loader;
    /**
     * @var HoistClassDynamicTypeOutline
     */
    protected $hoistingClass;

    public function __construct(SchemaOutlineInterface $baseSchema = null, ConfigurationInterface $configuration = null)
    {
        $this->baseSchema = $baseSchema ? : new BasicSchemaOutline();
        $this->loadingContext = new LoadingContext();
        $this->resolver = new LoaderResolver();
        $this->loader = new DelegatingLoader($this->resolver);
        $this->configuration = $configuration ? : new SchemaOutlineConfiguration();
        $this->processor = new Processor();

        $this->initHoisting();
    }

    protected function initHoisting()
    {
        $this->hoistingClass = new HoistClassDynamicTypeOutline($this->baseSchema);
        $this->baseSchema->addDynamicTypeOutline($this->hoistingClass);
    }

    /**
     * Registers loader to load schema outline
     *
     * @param ZorroLoaderInterface $loader
     * @return $this
     */
    public function registerLoader(ZorroLoaderInterface $loader)
    {
        $loader->setLoadingContext($this->loadingContext);
        $this->resolver->addLoader($loader);
        return $this;
    }

    /**
     * Loads file with schema outline definition
     *
     * @param string $file
     * @return self
     * @throws SchemaOutlineLoadingException when definition of schema outline is invalid
     */
    public function load($file)
    {
        $config = $this->loader->load($file);
        $config = $this->processor->processConfiguration($this->configuration, $config);
        try {
            $this->buildSchemaFromConfig($config);
        } catch (ZorroDataSchemaException $e) {
            throw new SchemaOutlineLoadingException('Invalid definition of schema outline in file "'.$file.'"', null, $e);
        }

        return $this;
    }

    protected function buildSchemaFromConfig($config)
    {
        if (isset($config['enums'])) {
            $this->buildEnums($config['enums']);
        }
        if (isset($config['classes'])) {
            $this->buildClasses($config['classes']);
        }
    }

    protected function buildClasses(array $classes)
    {
        $this->hoistingClass->setClassesConfig($classes);
        foreach ($classes as $className => $classMeta) {
            $classOutline = $this->hoistingClass->generate($className);
            $this->baseSchema->addTypeOutline($classOutline);
        }
    }

    protected function buildEnums(array $enums)
    {
        foreach ($enums as $enumName => $options) {
            $enumOutline = new EnumOutline($enumName, $options);
            $this->baseSchema->addTypeOutline($enumOutline);
        }
    }

    public function build()
    {
        return $this->baseSchema;
    }

    public function getResources()
    {
        return $this->loadingContext->getResources();
    }
}
