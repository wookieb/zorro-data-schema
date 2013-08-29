<?php
namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;
use Wookieb\ZorroDataSchema\Schema\Type\IntegerType;

class IntegerTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IntegerType;
     */
    private $object;

    protected function setUp()
    {
        $this->object = new IntegerType();
    }

    public function testThrowsExceptionWhenInvalidValueProvidedToCreate()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create an integer');
        $this->object->create(array());
    }

    public function testCreateWillConvertValueToInteger()
    {
        $this->assertSame(1, $this->object->create(1));
        $this->assertSame(1, $this->object->create('1'));

        $this->assertSame(0, $this->object->create(false));
        $this->assertSame(1, $this->object->create(true));
    }

    public function testCreateWillRestrictValueAccordingToNumberOfBites()
    {
        $this->object = new IntegerType(8);
        $this->assertSame(127, $this->object->create(300));

        $this->object = new IntegerType(16);
        $this->assertSame(32767, $this->object->create(39267));

        $this->object = new IntegerType(32);
        $this->assertSame(2147483647, $this->object->create(2847483647));
    }

    public function testValueHasTargetTypeIfIsAnIntegerAndInLessThanMaxValueForGivenNumOfBites()
    {
        $this->assertTrue($this->object->isTargetType(1));
        $this->assertFalse($this->object->isTargetType(false));

        $this->object = new IntegerType(8);
        $this->assertFalse($this->object->isTargetType(221));
        $this->assertFalse($this->object->isTargetType(10134));
        $this->assertTrue($this->object->isTargetType(127));
    }


    public function testThrowsExceptionWhenInvalidValueProvidedToExtract()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid value to extract');
        $this->object->extract(array());
    }

    public function testExtractWillConvertValueToInteger()
    {
        $this->assertSame(1, $this->object->extract(1));
        $this->assertSame(1, $this->object->extract('1'));

        $this->assertSame(0, $this->object->extract(false));
        $this->assertSame(1, $this->object->extract(true));
    }

    public function testExtractWillRestrictValueAccordingToNumberOfBites()
    {
        $this->object = new IntegerType(8);
        $this->assertSame(127, $this->object->extract(300));

        $this->object = new IntegerType(16);
        $this->assertSame(32767, $this->object->extract(39267));

        $this->object = new IntegerType(32);
        $this->assertSame(2147483647, $this->object->extract(2847483647));
    }
}
