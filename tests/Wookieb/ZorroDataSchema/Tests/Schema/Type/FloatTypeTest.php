<?php


namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;


use Wookieb\ZorroDataSchema\Schema\Type\FloatType;

class FloatTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FloatType
     */
    private $object;

    protected function setUp()
    {
        $this->object = new FloatType();
    }


    public function testThrowsExceptionWhenInvalidValueProvidedToCreate()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create a float');
        $this->object->create(array());
    }

    public function testCreateWillConvertToFloat()
    {
        $this->assertSame(1.0, $this->object->create(1));
        $this->assertSame(1.0, $this->object->create('1'));

        $this->assertSame(0.0, $this->object->create(false));
        $this->assertSame(1.0, $this->object->create(true));
    }

    public function testThrowsExceptionWhenInvalidValueProvidedToExtract()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid value to extract');
        $this->object->extract(array());
    }

    public function testExtractWillConvertToFloat()
    {
        $this->assertSame(1.0, $this->object->extract(1));
        $this->assertSame(1.0, $this->object->extract('1'));

        $this->assertSame(0.0, $this->object->extract(false));
        $this->assertSame(1.0, $this->object->extract(true));
    }

    public function testValueHasTargetTypeIfIsAFloat()
    {
        $this->assertTrue($this->object->isTargetType(1.1));
        $this->assertFalse($this->object->isTargetType(2));
        $this->assertFalse($this->object->isTargetType(false));
    }

    public function testValuePassTypeCheckIfIsAFloat()
    {
        $this->assertTrue($this->object->getTypeCheck()->isValidType(1.1));
        $this->assertFalse($this->object->getTypeCheck()->isValidType(2));
        $this->assertFalse($this->object->getTypeCheck()->isValidType(false));

        $this->assertSame('doubles', $this->object->getTypeCheck()->getTypeDescription());
    }
}
