<?php
namespace Wookieb\ZorroDataSchema\Tests\Type;

use Wookieb\ZorroDataSchema\Type\StringType;

class StringTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StringType
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $toStringObject;

    protected function setUp()
    {
        $this->object = new StringType();

        $this->toStringObject = $this->getMock('\stdClass', array('__toString'));

        $this->toStringObject->expects($this->any())
            ->method('__toString')
            ->will($this->returnValue('toString result'));
    }

    public function testConvertObjectsWithToStringMethodToString()
    {
        $this->assertSame('toString result', $this->object->create($this->toStringObject));
    }

    public function testConvertScalarValuesToString()
    {
        $this->assertSame('1', $this->object->create(1));
        $this->assertSame('2', $this->object->create('2'));
        $this->assertSame('3.2', $this->object->create(3.2));
        $this->assertSame('', $this->object->create(false));
        $this->assertSame('1', $this->object->create(true));
    }

    public function testThrowsExceptionWhenNonScalarValuesProvidedToCreateMethod()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create a string.');
        $this->object->create(array());
    }

    public function testThrowsExceptionWhenObjectWithoutToStringMethodProvidedToCreateMethod()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create a string.');
        $this->object->create(new \stdClass);
    }

    public function testExtractObjectsWithToStringMethodToString()
    {
        $this->assertSame('toString result', $this->object->extract($this->toStringObject));
    }

    public function testExtractConvertsDataToString()
    {
        $this->assertSame('1', $this->object->extract(1));
        $this->assertSame('2', $this->object->extract('2'));
        $this->assertSame('3.2', $this->object->extract(3.2));
        $this->assertSame('', $this->object->extract(false));
        $this->assertSame('1', $this->object->extract(true));
    }

    public function testThrowsExceptionWhenNonScalarValuesProvidedToExtractMethod()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to extract.');
        $this->object->extract(array());
    }

    public function testThrowsExceptionWhenObjectWithoutToStringMethodProvidedToExtractMethod()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to extract.');
        $this->object->extract(new \stdClass);
    }

    public function testIsValidType()
    {
        $this->assertTrue($this->object->isTargetType(''));
        $this->assertTrue($this->object->isTargetType('test'));
        $this->assertFalse($this->object->isTargetType(1));
        $this->assertFalse($this->object->isTargetType($this->toStringObject));
    }
}
