<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder;
use Wookieb\ZorroDataSchema\Loader\ZorroLoaderInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Builder\ImplementationBuilder;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\Builder\SchemaOutlineBuilder;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaBuilder
{

    private $implementationBuilder;
    private $schemaOutlineBuilder;
    private $schemaLinker;

    public function __construct(ImplementationBuilder $implementationBuilder = null,
                                SchemaOutlineBuilder $schemaOutlineBuilder = null,
                                SchemaLinker $schemaLinker = null)
    {
        $this->implementationBuilder = $implementationBuilder ? : $this->createImplementationBuilder();
        $this->schemaOutlineBuilder = $schemaOutlineBuilder ? : $this->createSchemaOutlineBuilder();
        $this->schemaLinker = $schemaLinker ? : $this->createSchemaLinker();
    }

    protected function createImplementationBuilder()
    {
        return new ImplementationBuilder();
    }

    protected function createSchemaOutlineBuilder()
    {
        return new SchemaOutlineBuilder();
    }

    protected function createSchemaLinker()
    {
        return new BasicSchemaLinker();
    }

    /**
     * @param ImplementationBuilder $implementationBuilder
     * @return self
     */
    public function setImplementationBuilder(ImplementationBuilder $implementationBuilder)
    {
        $this->implementationBuilder = $implementationBuilder;
        return $this;
    }

    /**
     * @return ImplementationBuilder
     */
    public function getImplementationBuilder()
    {
        return $this->implementationBuilder;
    }

    /**
     * @param SchemaOutlineBuilder $schemaOutlineBuilder
     * @return self
     */
    public function setSchemaOutlineBuilder(SchemaOutlineBuilder $schemaOutlineBuilder)
    {
        $this->schemaOutlineBuilder = $schemaOutlineBuilder;
        return $this;
    }

    /**
     * @return SchemaOutlineBuilder
     */
    public function getSchemaOutlineBuilder()
    {
        return $this->schemaOutlineBuilder;
    }

    /**
     * @param SchemaLinker $schemaLinker
     * @return self
     */
    public function setSchemaLinker(SchemaLinker $schemaLinker)
    {
        $this->schemaLinker = $schemaLinker;
        return $this;
    }

    /**
     * @return BasicSchemaLinker
     */
    public function getSchemaLinker()
    {
        return $this->schemaLinker;
    }

    /**
     * @param ZorroLoaderInterface $loader
     * @return self
     */
    public function registerLoader(ZorroLoaderInterface $loader)
    {
        $this->schemaOutlineBuilder->registerLoader(clone $loader);
        $this->implementationBuilder->registerLoader(clone $loader);
        return $this;
    }

    /**
     * Loads schema implementation definition resource
     *
     * @param string $resource
     * @return string
     */
    public function loadImplementation($resource)
    {
        $this->implementationBuilder->load($resource);
        return $this;
    }

    /**
     * Loads schema outline definition resource
     *
     * @param string $resource
     * @return self
     */
    public function loadSchema($resource)
    {
        $this->schemaOutlineBuilder->load($resource);
        return $this;
    }

    /**
     * Builds schema
     *
     * @param SchemaInterface $baseSchema
     * @return SchemaInterface
     */
    public function build(SchemaInterface $baseSchema = null)
    {
        $outline = $this->schemaOutlineBuilder->build();
        $implementation = $this->implementationBuilder->build();
        return $this->schemaLinker->build($outline, $implementation, $baseSchema);
    }

    /**
     * Returns list of resources used to create schema
     *
     * @return array
     */
    public function getResources()
    {
        return array_merge(
            $this->implementationBuilder->getResources(),
            $this->schemaOutlineBuilder->getResources()
        );
    }

}
