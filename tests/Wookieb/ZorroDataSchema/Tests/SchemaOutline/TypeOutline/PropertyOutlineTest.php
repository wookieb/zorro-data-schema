<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\TypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class PropertyOutlineTest extends ZorroUnit
{

    /**
     * @var PropertyOutline
     */
    protected $object;

    /**
     * @var TypeOutlineInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $type;

    protected function setUp()
    {
        $this->type = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface');
        $this->object = new PropertyOutline('tank', $this->type);
    }

    public function testNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        new PropertyOutline('', $this->type);
    }

    public function testGetName()
    {
        $this->assertSame('tank', $this->object->getName());
    }

    public function testGetType()
    {
        $this->assertSame($this->type, $this->object->getType());
    }

    public function testByDefaultDoesNotContainsDefaultValue()
    {
        $this->assertFalse($this->object->hasDefaultValue());
    }

    public function testSetDefaultValue()
    {
        $this->assertMethodChaining($this->object->setDefaultValue('default'), 'setDefaultValue');
        $this->assertSame('default', $this->object->getDefaultValue());
        $this->assertTrue($this->object->hasDefaultValue());
    }

    public function testIsNotNullableByDefault()
    {
        $this->assertFalse($this->object->isNullable());
    }

    public function testGetSetIsNullable()
    {
        $this->assertMethodChaining($this->object->setIsNullable(true), 'setIsNullable');
        $this->assertTrue($this->object->isNullable());
    }

    public function testCannotBeNullableWhenHasDefaultValue()
    {
        $msg = 'Cannot set property to be nullable since it has default value';
        $this->setExpectedException('\BadMethodCallException', $msg);
        $this->object->setDefaultValue('some')
            ->setIsNullable(true);
    }

    public function testCannotSetDefaultValueWhenIsNullable()
    {
        $msg = 'Cannot set default value of property since it\'s nullable';
        $this->setExpectedException('\BadMethodCallException', $msg);
        $this->object->setIsNullable(true)
            ->setDefaultValue('some value');
    }
}
