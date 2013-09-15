<?php


namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;


use Wookieb\TypeCheck\TypeCheck;
use Wookieb\ZorroDataSchema\Schema\Type\CollectionType;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class CollectionTypeTest extends ZorroUnit
{
    /**
     * @var CollectionType
     */
    protected $object;

    /**
     * @var TypeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $type;

    protected function setUp()
    {
        $this->type = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\Schema\Type\TypeInterface');
        $this->object = new CollectionType('repulsive', $this->type);
    }

    public function testNameCannotBeEmpty()
    {
        $this->cannotBeEmpty('Name of collection cannot be empty');
        new CollectionType('', $this->type);
    }

    public function testGetName()
    {
        $this->assertSame('repulsive', $this->object->getName());
    }

    public function testGetType()
    {
        $this->assertSame($this->type, $this->object->getType());
    }


    public function testIsTargetTypePositive()
    {
        $this->type->expects($this->atLeastOnce())
            ->method('isTargetType')
            ->will($this->returnValue(true));

        $tab = range(1, 10);
        $this->assertTrue($this->object->isTargetType($tab));
    }

    public function testIsTargetTypeNegative()
    {
        $this->type->expects($this->exactly(3))
            ->method('isTargetType')
            ->will($this->onConsecutiveCalls(true, true, false));

        $tab = range(1, 10);
        $this->assertFalse($this->object->isTargetType($tab));
    }

    public function testGetTypeCheck()
    {
        $this->type->expects($this->once())
            ->method('getTypeCheck')
            ->will($this->returnValue(TypeCheck::strings()));

        $this->assertFalse($this->object->getTypeCheck()->isValidType(array(1, 2, 'foo')));
        $this->assertTrue($this->object->getTypeCheck()->isValidType(array('foo', 'bar')));

        $this->assertSame('traversable structures that contains strings', $this->object->getTypeCheck()->getTypeDescription());
    }

    public function testCreateShouldAcceptOnlyArraysAndTraversableObjects()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create collection');
        $this->object->create(false);
    }

    public function testCreate()
    {
        $transform = function ($value) {
            return $value.' created';
        };
        $this->type->expects($this->atLeastOnce())
            ->method('create')
            ->will($this->returnCallback($transform));

        $this->type->expects($this->atLeastOnce())
            ->method('isTargetType')
            ->will($this->returnValue(false));

        $tab = range(1, 10);
        $expected = array_map($transform, $tab);
        $this->assertEquals($expected, $this->object->create($tab));

        $value = new \ArrayIterator(range(1, 10));
        $this->assertEquals($expected, $this->object->create($value));
    }


    public function testExtractShouldAcceptOnlyArraysAndTraversableObjects()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to extract');
        $this->object->extract(false);
    }

    public function testExtract()
    {
        $transform = function ($value) {
            return $value.' extracted';
        };
        $this->type->expects($this->atLeastOnce())
            ->method('extract')
            ->will($this->returnCallback($transform));

        $tab = range(1, 10);
        $expected = array_map($transform, $tab);
        $this->assertEquals($expected, $this->object->extract($tab));

        $value = new \ArrayIterator($tab);
        $this->assertEquals($expected, $this->object->extract($value));
    }

}
