<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema;

use Wookieb\ZorroDataSchema\Schema\Schema;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;

class SchemaTest extends ZorroUnit
{
    /**
     * @var Schema
     */
    protected $object;

    /**
     * @var TypeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $type;

    protected function setUp()
    {
        $this->object = new Schema();
        $this->type = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\Schema\Type\TypeInterface');
    }

    public function testRegisterType()
    {
        $this->assertMethodChaining($this->object->registerType('VibrantAcknowledgment', $this->type), 'registerType');
        $this->assertSame($this->type, $this->object->getType('VibrantAcknowledgment'));
        $this->assertTrue($this->object->hasType('VibrantAcknowledgment'));
    }

    /**
     * @depends testRegisterType
     */
    public function testIteration()
    {
        $this->assertSame(0, $this->object->getIterator()->count());
        $this->object->registerType('VibrantAcknowledgment', $this->type);
        $this->assertSame(1, $this->object->getIterator()->count());
    }

    public function testExceptionWhenTypeNotExist()
    {
        $msg = 'Type "VibrantAcknowledgment" does not exist';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\TypeNotFoundException', $msg);
        $this->object->getType('VibrantAcknowledgment');
    }
}
