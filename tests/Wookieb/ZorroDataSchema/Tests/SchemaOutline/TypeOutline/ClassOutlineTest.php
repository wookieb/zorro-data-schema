<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\TypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ClassOutlineTest extends ZorroUnit
{

    /**
     * @var ClassOutline
     */
    protected $object;

    private $type;

    protected function setUp()
    {
        $this->object = new ClassOutline('income');
        $this->type = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface');
    }

    public function testGetName()
    {
        $this->assertSame('income', $this->object->getName());
    }

    public function testAddProperty()
    {
        $property = new PropertyOutline('property', $this->type);
        $this->assertMethodChaining($this->object->addProperty($property), 'addProperty');
        $this->assertEquals(array('property' => $property), $this->object->getProperties());
    }

    public function testAddingPropertiesFromConstructor()
    {
        $properties = array(
            'property' => new PropertyOutline('property', $this->type),
            'titanic' => new PropertyOutline('titanic', $this->type)
        );

        $this->object = new ClassOutline('income', $properties);
        $this->assertEquals($properties, $this->object->getProperties());
    }

    public function testGetParentType()
    {
        $parent = new ClassOutline('banker');
        $this->object = new ClassOutline('income', array(), $parent);
        $this->assertSame($parent, $this->object->getParentClass());
    }
}

