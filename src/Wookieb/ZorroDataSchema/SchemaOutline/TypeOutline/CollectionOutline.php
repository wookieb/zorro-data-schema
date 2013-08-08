<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class CollectionOutline extends AbstractTypeOutline
{
    private $elementsType;

    public function __construct($name, TypeOutlineInterface $elementsType)
    {
        parent::__construct($name);
        $this->setElementsType($elementsType);
    }

    /**
     * @param TypeOutlineInterface $typeOutline
     * @return self
     */
    private function setElementsType(TypeOutlineInterface $typeOutline)
    {
        $this->elementsType = $typeOutline;
        return $this;
    }

    /**
     * @return TypeOutlineInterface
     */
    public function getElementsType()
    {
        return $this->elementsType;
    }
}