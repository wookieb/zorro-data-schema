<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Builder;
use Symfony\Component\Config\Definition\ConfigurationInterface as Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Wookieb\ZorroDataSchema\Exception\ImplementationLoadingException;
use Wookieb\ZorroDataSchema\Loader\LoadingContext;
use Wookieb\ZorroDataSchema\Loader\ZorroLoaderInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\BasicImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\GlobalClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\PropertyImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StandardStyles;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\Styles;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ImplementationBuilder
{
    private $loadingContext;
    private $resolver;
    private $loader;
    private $configuration;
    private $processor;
    private $implementation;
    private $styles;

    public function __construct(ImplementationInterface $baseImplementation = null,
                                Styles $styles = null,
                                Configuration $configuration = null)
    {
        $this->loadingContext = new LoadingContext();
        $this->resolver = new LoaderResolver();
        $this->loader = new DelegatingLoader($this->resolver);
        $this->configuration = $configuration ? : new ImplementationConfiguration();
        $this->processor = new Processor();
        $this->styles = $styles ? : new StandardStyles();
        $this->implementation = $baseImplementation ? : $this->createStandardImplementation();
    }

    private function createStandardImplementation()
    {
        return new BasicImplementation(new ClassMap(), new GlobalClassTypeImplementation());
    }

    public function registerLoader(ZorroLoaderInterface $loader)
    {
        $loader->setLoadingContext($this->loadingContext);
        $this->resolver->addLoader($loader);
        return $this;
    }

    public function load($file)
    {
        $config = $this->loader->load($file);
        $config = $this->processor->processConfiguration($this->configuration, $config);
        $this->buildImplementationFromConfig($config);
    }

    private function buildImplementationFromConfig($config)
    {
        if (isset($config['global'])) {
            $this->applyGlobalConfig($config['global']);
        }

        if (isset($config['classes'])) {
            $this->buildImplementationOfClasses($config['classes']);
        }
    }

    protected function applyGlobalConfig($globalConfig)
    {
        $globalClassOptions = $this->implementation->getGlobalClassImplementation();
        try {
            if (isset($globalConfig['accessors'])) {
                $accessorsConfig = $globalConfig['accessors'];

                if (isset($accessorsConfig['style'])) {
                    $styleName = $accessorsConfig['style'];
                    if (!$this->styles->hasStyle($styleName)) {
                        $msg = sprintf('Cannot set global setters and getters style to "%s" since style not exist', $styleName);
                        throw new ImplementationLoadingException($msg);
                    }
                    $globalClassOptions->setAccessorsStyle($this->styles->getStyle($styleName))
                        ->setAccessorsEnabled(true);
                }
                if (isset($accessorsConfig['enabled'])) {
                    $globalClassOptions->setAccessorsEnabled($accessorsConfig['enabled']);
                }
            }

            if (isset($globalConfig['namespaces'])) {
                $classMap = $this->implementation->getClassMap();
                foreach ((array)$globalConfig['namespaces'] as $namespace) {
                    $classMap->registerNamespace($namespace);
                }
            }
        } catch (\InvalidArgumentException $e) {
            throw new ImplementationLoadingException('Invalid global implementation settings', null, $e);
        }
    }

    protected function buildImplementationOfClasses(array $classes)
    {
        foreach ($classes as $typeName => $options) {
            try {
                $this->implementation->registerClassTypeImplementation(
                    $this->createClassImplementation($typeName, $options)
                );
            } catch (\LogicException $e) {
                $msg = 'Invalid implementation settings of class type "'.$typeName.'"';
                throw new ImplementationLoadingException($msg, null, $e);
            }
        }
    }

    protected function createClassImplementation($typeName, $options)
    {
        $globalClassOptions = $this->implementation->getGlobalClassImplementation();
        $classImplementation = new ClassTypeImplementation($typeName);

        // default class implementation from global class implementation settings
        $classImplementation->setAccessorsEnabled($globalClassOptions->isAccessorsEnabled());
        if ($globalClassOptions->isAccessorsEnabled() && $globalClassOptions->getAccessorsStyle()) {
            $classImplementation->setAccessorsStyle($globalClassOptions->getAccessorsStyle());
        }

        if (isset($options['class'])) {
            $classImplementation->setClassName($options['class']);
        }

        if (isset($options['accessors'])) {
            $accessors = $options['accessors'];
            if (isset($accessors['style'])) {
                $styleName = $accessors['style'];

                if (!$this->styles->hasStyle($styleName)) {
                    $msg = sprintf('Cannot set global setters and getters style to "%s" since style does not exist', $styleName);
                    throw new ImplementationLoadingException($msg);
                }
                $classImplementation->setAccessorsStyle($this->styles->getStyle($styleName))
                    ->setAccessorsEnabled(true);
            }

            if (isset($accessors['enabled'])) {
                $classImplementation->setAccessorsEnabled($accessors['enabled']);
            }
        }

        foreach ((array)@$options['properties'] as $propertyName => $propertyOptions) {
            $propertyImplementation = $this->createPropertyImplementation($propertyName, $propertyOptions, $classImplementation);
            $classImplementation->addPropertyImplementation($propertyImplementation);
        }
        return $classImplementation;
    }

    protected function createPropertyImplementation($propertyName, $propertyOptions, ClassTypeImplementation $classImplementation)
    {
        $propertyImplementation = new PropertyImplementation($propertyName);
        $targetPropertyName = $propertyName;
        if (isset($propertyOptions['as'])) {
            $propertyImplementation->setTargetPropertyName($propertyOptions['as']);
            $targetPropertyName = $propertyOptions['as'];
        }

        // accessors enabled
        $accessorsEnabled = $classImplementation->isAccessorsEnabled();
        if (isset($propertyOptions['accessors']['enabled'])) {
            $accessorsEnabled = (bool)$propertyOptions['accessors']['enabled'];
        }

        // accessors names
        if (array_key_exists('setter', $propertyOptions)) {
            $propertyImplementation->setSetter($propertyOptions['setter']);
        } else if ($accessorsEnabled) {
            $style = $classImplementation->getAccessorsStyle();
            if (!$style) {
                $msg = 'Cannot generate setter name for property "'.$propertyName.'".
                                Define that name or naming style of setters and getters';
                throw new \BadMethodCallException($msg);
            }
            $propertyImplementation->setSetter($style->generateSetterName($targetPropertyName));
        }

        if (array_key_exists('getter', $propertyOptions)) {
            $propertyImplementation->setGetter($propertyOptions['getter']);
        } elseif ($accessorsEnabled) {
            $style = $classImplementation->getAccessorsStyle();
            if (!$style) {
                $msg = 'Cannot generate getter name for property "'.$propertyName.'".
                                Define that name or naming style of setters and getters';
                throw new \BadMethodCallException($msg);
            }
            $propertyImplementation->setGetter($style->generateGetterName($targetPropertyName));
        }
        return $propertyImplementation;
    }

    public function build()
    {
        return $this->implementation;
    }

    public function getResources()
    {
        return $this->loadingContext->getResources();
    }
}
