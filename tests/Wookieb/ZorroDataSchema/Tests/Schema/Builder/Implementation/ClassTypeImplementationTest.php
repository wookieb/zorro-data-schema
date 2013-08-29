<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation;

use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\PropertyImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\CamelCaseStyle;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ClassTypeImplementationTest extends ZorroUnit
{
    /**
     * @var ClassTypeImplementation
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ClassTypeImplementation('home');
    }

    public function testClassTypeImplementationCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        new ClassTypeImplementation('');
    }

    public function testGetName()
    {
        $this->assertSame('home', $this->object->getName());
    }

    public function testSetGetClassName()
    {
        $this->assertMethodChaining($this->object->setClassName('Overcooked'), 'setClassName');
        $this->assertSame('Overcooked', $this->object->getClassName());
    }

    public function testAddPropertyImplementation()
    {
        $propertyImplementation = new PropertyImplementation('property');
        $this->assertMethodChaining($this->object->addPropertyImplementation($propertyImplementation), 'addPropertyImplementation');
        $this->assertSame($propertyImplementation, $this->object->getImplementationForProperty('property'));
    }

    public function testDefaultPropertyImplementationWithoutAccessors()
    {
        $this->object->setAccessorsEnabled(false);
        $propertyName = 'inferior';
        $propertyImplementation = $this->object->getImplementationForProperty($propertyName);
        $this->assertSame($propertyName, $propertyImplementation->getName(), 'invalid property name');
        $this->assertSame($propertyName, $propertyImplementation->getTargetPropertyName(), 'invalid target property name');
        $this->assertNull($propertyImplementation->getSetter(), 'setter name should be null');
        $this->assertNull($propertyImplementation->getGetter(), 'getter name should be null');
    }

    /**
     * @depends testDefaultPropertyImplementationWithoutAccessors
     */
    public function testDefaultPropertyImplementationWithAccessorsButWithoutStyle()
    {
        $this->object->setAccessorsEnabled(true);
        $propertyName = 'inferior';
        $this->setExpectedException('\BadMethodCallException', 'Cannot generate setters nad getters name without naming style');
        $this->object->getImplementationForProperty($propertyName);
    }

    /**
     * @depends testDefaultPropertyImplementationWithAccessorsButWithoutStyle
     */
    public function testDefaultPropertyImplementationWithAccessorsAndStyle()
    {
        $this->object->setAccessorsEnabled(true)
            ->setAccessorsStyle(new CamelCaseStyle());
        $propertyName = 'inferior';
        $propertyImplementation = $this->object->getImplementationForProperty($propertyName);
        $this->assertSame('setInferior', $propertyImplementation->getSetter(), 'setter name should be null');
        $this->assertSame('getInferior', $propertyImplementation->getGetter(), 'getter name should be null');
    }
}
