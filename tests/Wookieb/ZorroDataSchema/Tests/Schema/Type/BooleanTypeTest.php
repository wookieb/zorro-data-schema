<?php
namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;

use Wookieb\ZorroDataSchema\Schema\Type\BooleanType;

class BooleanTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BooleanType
     */
    private $object;

    protected function setUp()
    {
        $this->object = new BooleanType();
    }

    public function testConvertsEverythingToBool()
    {
        $this->assertTrue($this->object->create(1));
        $this->assertTrue($this->object->create(array(1)));
        $this->assertTrue($this->object->create(new \stdClass()));
        $this->assertFalse($this->object->create(0));
        $this->assertFalse($this->object->create(false));
    }

    public function testExtractsEverythingToBool()
    {
        $this->assertTrue($this->object->extract(1));
        $this->assertTrue($this->object->extract(array(1)));
        $this->assertTrue($this->object->extract(new \stdClass()));
        $this->assertFalse($this->object->extract(0));
        $this->assertFalse($this->object->extract(false));
    }

    public function testIsTargetType()
    {
        $this->assertTrue($this->object->isTargetType(true));
        $this->assertTrue($this->object->isTargetType(false));
        $this->assertFalse($this->object->isTargetType(-1));
        $this->assertFalse($this->object->isTargetType(''));
    }

    public function testGetTypeCheck()
    {
        $this->assertTrue($this->object->getTypeCheck()->isValidType(true));
        $this->assertTrue($this->object->getTypeCheck()->isValidType(false));
        $this->assertFalse($this->object->getTypeCheck()->isValidType(-1));
        $this->assertFalse($this->object->getTypeCheck()->isValidType(''));

        $this->assertSame('booleans', $this->object->getTypeCheck()->getTypeDescription());
    }
}
