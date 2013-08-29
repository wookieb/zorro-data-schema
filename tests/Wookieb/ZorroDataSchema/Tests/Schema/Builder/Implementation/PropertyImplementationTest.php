<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation;

use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\PropertyImplementation;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class PropertyImplementationTest extends ZorroUnit
{
    /**
     * @var PropertyImplementation
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new PropertyImplementation('kosher');
    }

    public function testPropertyNameCannotBeEmpty()
    {
        $this->cannotBeEmpty('Property name cannot be empty');
        new PropertyImplementation('');
    }

    public function testGetPropertyName()
    {
        $this->assertSame('kosher', $this->object->getName());
    }

    public function testSetSetterName()
    {
        $this->assertMethodChaining($this->object->setSetter('setKosher'), 'setSetter');
        $this->assertSame('setKosher', $this->object->getSetter());
    }

    public function testSetterNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->setSetter('');
    }

    public function testGetSetterName()
    {
        $this->assertMethodChaining($this->object->setGetter('getKosher'), 'getKosher');
        $this->assertSame('getKosher', $this->object->getGetter());
    }

    public function testGetterNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->setGetter('');
    }

    public function testSetGetTargetPropertyName()
    {
        $this->assertMethodChaining($this->object->setTargetPropertyName('zoo'), 'setTargetPropertyName');
        $this->assertSame('zoo', $this->object->getTargetPropertyName());
    }

    public function testTargetPropertyNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->setTargetPropertyName('');
    }
}
