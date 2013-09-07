<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;

/**
 * Generates classes by providing their config
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class HoistClassDynamicTypeOutline implements DynamicTypeOutlineInterface
{
    private $classesConfig;
    /**
     * @var SchemaOutlineInterface
     */
    private $schemaOutline;

    private $loadedClasses = array();

    /**
     * @param SchemaOutlineInterface $schemaOutline reference to current outline of schema
     */
    public function __construct(SchemaOutlineInterface $schemaOutline)
    {
        $this->schemaOutline = $schemaOutline;
    }

    /**
     * Set config of classes outline
     *
     * @param array $config
     */
    public function setClassesConfig(array $config)
    {
        $this->classesConfig = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function isAbleToGenerate($name)
    {
        return isset($this->classesConfig[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function generate($name)
    {
        if (!$this->isAbleToGenerate($name)) {
            throw new UnableToGenerateTypeOutlineException('Class type "'.$name.'" does not exist');
        }

        if (isset($this->loadedClasses[$name])) {
            return $this->loadedClasses[$name];
        }

        $classMeta = $this->classesConfig[$name];
        $parentClass = null;
        if (isset($classMeta['extend'])) {
            $parentClass = $this->schemaOutline->getTypeOutline($classMeta['extend']);
            if (!$parentClass instanceof ClassOutline) {
                $msg = 'Parent class "'.$classMeta['extend'].'" must be a class outline instance';
                throw new UnableToGenerateTypeOutlineException($msg);
            }
        }

        $classOutline = new ClassOutline($name, array(), $parentClass);
        $this->loadedClasses[$name] = $classOutline;

        if (isset($classMeta['properties'])) {
            foreach ($classMeta['properties'] as $propertyName => $property) {
                $propertyOutline = new PropertyOutline($propertyName, $this->schemaOutline->getTypeOutline($property['type']));

                if (isset($property['default'])) {
                    $propertyOutline->setDefaultValue($property['default']);
                }

                if (isset($property['nullable'])) {
                    $propertyOutline->setIsNullable($property['nullable']);
                }

                $classOutline->addProperty($propertyOutline);
            }
        }
        unset($this->loadedClasses[$name]);
        return $classOutline;
    }
}
