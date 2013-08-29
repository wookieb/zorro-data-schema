<?php

namespace Wookieb\ZorroDataSchema\Schema;
use Symfony\Component\Config\ConfigCache;

/**
 * Handle caching of schema
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class SchemaCache
{
    private $configCache;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->configCache = new ConfigCache($file, true);
    }

    /**
     * Checks whether cache is still fresh
     *
     * @return boolean
     */
    public function isFresh()
    {
        return $this->configCache->isFresh();
    }

    /**
     * Saves schema to cache
     *
     * @param SchemaInterface $schema
     * @param array $resources list of resources used to create schema
     * @return self
     */
    public function write(SchemaInterface $schema, array $resources)
    {
        $this->configCache->write(serialize($schema), $resources);
        return $this;
    }

    /**
     * Returns cached schema
     *
     * @return SchemaInterface
     * @throws \BadMethodCallException when schema cache file does not exist
     */
    public function get()
    {
        $file = (string)$this->configCache;
        if (!file_exists($file)) {
            throw new \BadMethodCallException('Schema cache file "'.$file.'" not exist');
        }
        return unserialize(file_get_contents($file));
    }
}
