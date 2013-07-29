<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Loaders\SchemaLoaderInterface;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;
use Wookieb\ZorroDataSchema\Type\ObjectType;
use Wookieb\ZorroDataSchema\Type\PropertyDefinition\PropertyDefinition;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaBuilder
{
    protected $schema;
    protected $loadingContext;
    protected $loader;
    protected $classMap;
    protected $processor;
    protected $configDefinition;

    public function __construct(SchemaInterface $schema, ConfigurationInterface $config = null)
    {
        $this->schema = $schema;
        $this->loadingContext = new LoadingContext();
        $this->loaderResolver = new LoaderResolver();
        $this->loader = new DelegatingLoader($this->loaderResolver);
        $this->processor = new Processor();
        $this->configDefinition = $config ? : new SchemaConfiguration();
    }

    public function addLoader(SchemaLoaderInterface $loader)
    {
        $loader->setLoadingContext($this->loadingContext);
        $this->loaderResolver->addLoader($loader);
        return $this;
    }

    public function getClassMap()
    {
        return $this->classMap;
    }

    public function load($file)
    {
        $config = $this->loader->load($file);
        $config = $this->processor->processConfiguration($this->configDefinition, $config);
        $this->buildSchemaFromConfig($config);
    }

    protected function buildSchemaFromConfig(array $config)
    {
        if (isset($config['objects'])) {
            $this->buildObjects($config['objects']);
        }
    }

    protected function buildObjects(array $objects)
    {
        foreach ($objects as $objectName => $properties) {
            $type = new ObjectType($objectName);
            foreach ($properties as $propertyName => $propertyDefinition) {
                $propertyType = $this->schema->getType($propertyDefinition['type']);

                $definition = new PropertyDefinition($propertyType);
                if (array_key_exists('nullable', $propertyDefinition)) {
                    $definition->setIsNullable($propertyDefinition['nullable']);
                }
                if (array_key_exists('default', $propertyDefinition)) {
                    $definition->setDefaultValue($propertyDefinition['default']);
                }

                $type->setProperty($propertyName, $definition);
            }
            $this->schema->registerType($type);
        }
    }


    public function getSchema()
    {
        return $this->schema;
    }
}