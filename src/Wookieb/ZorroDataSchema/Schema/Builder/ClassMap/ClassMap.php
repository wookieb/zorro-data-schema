<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\ClassMap;
use Wookieb\ZorroDataSchema\Exception\ClassNotFoundException;


/**
 * Standard class map implementation
 * Attempts to search classes in registered namespaces basing on type name where type name becomes a class name.
 * Additionally dot (".") characters in type name are replaced by namespace separator ("\").
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassMap implements ClassMapInterface
{
    private $classes = array();
    private $namespaces = array();

    /**
     * {@inheritDoc}
     */
    public function registerNamespace($namespace)
    {
        $this->namespaces[] = trim($namespace, '\\').'\\';
        return $this;
    }

    protected function searchClassInNamespaces($typeName)
    {
        $typeName = str_replace('.', '\\', $typeName);
        foreach ($this->namespaces as $namespace) {
            $className = $namespace.$typeName;
            if (class_exists($className)) {
                return $className;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getClass($typeName)
    {
        if (isset($this->classes[$typeName])) {
            return $this->classes[$typeName];
        }
        $foundClass = $this->searchClassInNamespaces($typeName);
        if ($foundClass) {
            return $foundClass;
        }
        throw new ClassNotFoundException('No class name defined for type "'.$typeName.'"');
    }

    /**
     * {@inheritDoc}
     */
    public function registerClass($typeName, $class)
    {
        $this->classes[$typeName] = $class;
        return $this;
    }
}
