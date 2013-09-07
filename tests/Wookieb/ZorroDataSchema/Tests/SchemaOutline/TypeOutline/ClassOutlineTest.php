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

    public function isSubclassOfProvider()
    {
        return array(
            'class without parent' => array(
                new ClassOutline('Admin'),
                false
            ),
            'class with parent and not matching name' => array(
                new ClassOutline('Admin', array(), new ClassOutline('Chef')),
                false
            ),
            'class with parent and matching name' => array(
                new ClassOutline('Admin', array(), new ClassOutline('User')),
                true
            ),
            'class with parent and not matching name, but that parent has a parent with matching name' => array(
                new ClassOutline('Admin', array(), new ClassOutline('Admin2', array(), new ClassOutline('User'))),
                true
            ),
            'class with parent and not matching name, but that parent has a parent with not matching name' => array(
                new ClassOutline('Admin', array(), new ClassOutline('Admin2', array(), new ClassOutline('SuperAdmin'))),
                false
            )
        );
    }

    /**
     * @dataProvider isSubclassOfProvider
     */
    public function testIsSubclassOf(ClassOutline $classOutline, $result)
    {
        $this->assertSame($result, $classOutline->isSubclassOf('User'));
    }
}

