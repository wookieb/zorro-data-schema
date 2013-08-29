<?php

namespace Wookieb\ZorroDataSchema\Loader;
use Symfony\Component\Yaml\Yaml as YamlParser;
use Symfony\Component\Config\Resource\FileResource;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class YamlLoader extends BaseFileLoader
{

    private $yamlParser;

    /**
     * Loads a resource.
     *
     * @param mixed $resource
     * @param string $type
     * @return array
     * @throws \InvalidArgumentException when file does not exist
     */
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);

        if (!$this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        $config = $this->yamlParser->parse(file_get_contents($path));
        $this->loadingContext->addResource(new FileResource($path));

        if (!is_array($config)) {
            return array();
        }

        $configs = array(&$config);
        if (array_key_exists('import', $config)) {
            foreach ($config['import'] as $file) {
                $importConfig = $this->import($file);
                $configs = array_merge($configs, $importConfig);
            }
            unset($config['import']);
        }

        return array_reverse($configs);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'yaml' === $type);
    }
}
