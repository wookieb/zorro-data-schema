<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\ClassMap;
use Wookieb\ZorroDataSchema\Exception\NoClassForSuchTypeException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassMap implements ClassMapInterface
{
    private $classes = array();
    private $namespaces = array();

    /**
     * Register namespace where to looking for class names with same name as type name
     *
     * @param string $namespace
     * @return self
     */
    public function registerNamespace($namespace)
    {
        $this->namespaces[] = trim($namespace, '\\').'\\';
        return $this;
    }

    private function searchClassInNamespaces($typeName)
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
        throw new NoClassForSuchTypeException('No class name defined for object of type "'.$typeName.'"');
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