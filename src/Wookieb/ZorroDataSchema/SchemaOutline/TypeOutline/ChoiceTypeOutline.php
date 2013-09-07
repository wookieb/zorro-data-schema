<?php

namespace Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ChoiceTypeOutline extends AbstractTypeOutline
{
    private $typeOutlines = array();

    /**
     * @param TypeOutlineInterface $typeOutline
     * @return self
     * @throws \InvalidArgumentException when argument is instance of ChoiceTypeOutline
     */
    public function addTypeOutline(TypeOutlineInterface $typeOutline)
    {
        if ($typeOutline instanceof ChoiceTypeOutline) {
            $msg = 'Choice type cannot contain other Choice types';
            throw new \InvalidArgumentException($msg);
        }
        $this->typeOutlines[$typeOutline->getName()] = $typeOutline;
        return $this;
    }

    /**
     * @return array
     */
    public function getTypeOutlines()
    {
        return $this->typeOutlines;
    }
} 
