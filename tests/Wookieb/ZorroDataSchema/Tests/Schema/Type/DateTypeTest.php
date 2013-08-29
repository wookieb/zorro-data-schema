<?php
namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;

use Wookieb\ZorroDataSchema\Exception\InvalidValueException;
use Wookieb\ZorroDataSchema\Schema\Type\DateType;

class DateTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateType
     */
    private $object;

    protected function setUp()
    {
        $this->object = new DateType();
    }

    public function testThrowsExceptionWhenValueToCreateDateIsNotStringOrNumber()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create a Date');
        $this->object->create(false);
    }

    private function assertSameDate($expected, $date, $message = null)
    {
        if (!$date instanceof \DateTIme) {
            throw new \LogicException('Provided value is not a DateTime object');
        }
        $this->assertSame($expected, $date->format(\DateTime::ISO8601), $message);
    }

    public function testCreate()
    {
        $this->assertSameDate('2013-08-13T18:40:12+0000', $this->object->create(1376419212), 'Invalid handling of timestamp');
        $this->assertSameDate('2013-08-10T10:00:12+0000', $this->object->create('2013-08-10T10:00:12+0000'), 'Invalid handling of date string');
    }

    public function testThrowsExceptionWhenValueIsAStringWhichCannotBeConvertedToDate()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid format of data to create a Date');
        try {
            $this->object->create('programming motherfucker');
        } catch (InvalidValueException $e) {
            $this->assertInstanceOf('\Exception', $e->getPrevious());
            throw $e;
        }
    }

    public function testJustReturnsValueIfItsADatetime()
    {
        $this->assertSameDate('2013-08-13T18:40:12+0000', $this->object->create(new \DateTime('2013-08-13T18:40:12+0000')));
    }

    public function testExtractReturnsIsoDateString()
    {
        $datetime = new \DateTime('2013-08-13T18:40:12+0000');
        $this->assertSame('2013-08-13T18:40:12+0000', $this->object->extract($datetime));
    }

    public function testExtractThrowsExceptionWhenProvidedValueIsNotADatetime()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid value to extract');
        $this->object->extract('moat');
    }

    public function testValueHasTargetTypeIfIsADatetimeObject()
    {
        $this->assertTrue($this->object->isTargetType(new \DateTime()));
        $this->assertFalse($this->object->isTargetType(false));
    }
}
