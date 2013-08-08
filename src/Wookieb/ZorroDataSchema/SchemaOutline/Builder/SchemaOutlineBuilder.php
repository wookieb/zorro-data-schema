<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\Builder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Wookieb\ZorroDataSchema\Exception\InvalidTypeException;
use Wookieb\ZorroDataSchema\SchemaOutline\Builder\Loaders\SchemaOutlineLoaderInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\EnumOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaOutlineBuilder
{
    private $baseSchema;

    private $loadingContext;

    private $resolver;
    private $loader;

    public function __construct(SchemaOutlineInterface $baseSchema, ConfigurationInterface $configuration = null)
    {
        $this->baseSchema = $baseSchema;
        $this->loadingContext = new LoadingContext();
        $this->resolver = new LoaderResolver();
        $this->loader = new DelegatingLoader($this->resolver);
        $this->configuration = $configuration ? : new SchemaOutlineConfiguration();
        $this->processor = new Processor();
    }

    public function registerLoader(SchemaOutlineLoaderInterface $loader)
    {
        $loader->setLoadingContext($this->loadingContext);
        $this->resolver->addLoader($loader);
        return $this;
    }

    public function load($file)
    {
        $config = $this->loader->load($file);
        $config = $this->processor->processConfiguration($this->configuration, $config);
        $this->buildSchemaFromConfig($config);
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
        foreach ($classes as $className => $classMeta) {
            $parentClass = null;
            if (isset($classMeta['extend'])) {
                $parentClass = $this->baseSchema->getType($classMeta['extend']);
                if (!$parentClass instanceof ClassOutline) {
                    $msg = 'Parent class "'.$classMeta['extend'].'" must be a class outline instance';
                    throw new InvalidTypeException(array($msg));
                }
            }
            $classOutline = new ClassOutline($className, array(), $parentClass);
            if (isset($classMeta['properties'])) {
                foreach ($classMeta['properties'] as $propertyName => $property) {
                    $propertyOutline = new PropertyOutline($propertyName, $this->baseSchema->getType($property['type']));
                    if (isset($property['default'])) {
                        $propertyOutline->setDefaultValue($property['default']);
                    }
                    $propertyOutline->setIsNullable($property['nullable']);
                    $classOutline->addProperty($propertyOutline);
                }
            }
            $this->baseSchema->addType($classOutline);
        }
    }

    protected function buildEnums(array $enums)
    {
        foreach ($enums as $enumName => $options) {
            $enumOutline = new EnumOutline($enumName, $options);
            $this->baseSchema->addType($enumOutline);
        }
    }

    public function build()
    {
        return $this->baseSchema;
    }
}