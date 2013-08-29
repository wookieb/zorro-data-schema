<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation;

use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\GlobalClassTypeImplementation;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class GlobalClassTypeImplementationTest extends ZorroUnit
{
    /**
     * @var GlobalClassTypeImplementation
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new GlobalClassTypeImplementation();
    }

    public function testSetGetAccessorsStyle()
    {
        $style = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StyleInterface');
        $this->assertMethodChaining($this->object->setAccessorsStyle($style), 'setAccessorsStyle');
        $this->assertSame($style, $this->object->getAccessorsStyle());
    }

    public function testAccessorsAreDisabledByDefault()
    {
        $this->assertFalse($this->object->isAccessorsEnabled());
    }

    public function testEnablingAccessors()
    {
        $this->assertMethodChaining($this->object->setAccessorsEnabled(true), 'setAccessorsEnabled');
        $this->assertTrue($this->object->isAccessorsEnabled());
    }

    public function testDisablingAccessors()
    {
        $this->assertMethodChaining($this->object->setAccessorsEnabled(false), 'setAccessorsEnabled');
        $this->assertFalse($this->object->isAccessorsEnabled());
    }
}
