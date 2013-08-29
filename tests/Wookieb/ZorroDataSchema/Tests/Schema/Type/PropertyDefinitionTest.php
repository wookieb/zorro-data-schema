<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;
use Wookieb\ZorroDataSchema\Schema\Type\PropertyDefinition;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class PropertyDefinitionTest extends ZorroUnit
{
    /**
     * @var PropertyDefinition
     */
    protected $object;

    /**
     * @var StringType
     */
    private $type;

    protected function setUp()
    {
        $this->type = new StringType();
        $this->object = new PropertyDefinition('constantMonth', $this->type);
    }

    public function testGetName()
    {
        $this->assertSame('constantMonth', $this->object->getName());
    }

    public function testGetType()
    {
        $this->assertSame($this->type, $this->object->getType());
    }

    public function testNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        new PropertyDefinition('', $this->type);
    }

    public function testByDefaultPropertyDoesNotHaveADefaultValue()
    {
        $this->assertFalse($this->object->hasDefaultValue());
    }

    public function testSetDefaultValue()
    {
        $this->assertMethodChaining($this->object->setDefaultValue('zesty decimal'), 'setDefaultValue');
        $this->assertTrue($this->object->hasDefaultValue());
        $this->assertSame('zesty decimal', $this->object->getDefaultValue());
    }

    public function testGettingDefaultValueShouldBeUnableWhenDefaultValueIsNotSet()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\NoDefaultValueException', 'No default value');
        $this->object->getDefaultValue();
    }

    public function testDefaultValueShouldBeConvertedToTargetType()
    {
        $this->object->setDefaultValue(null);
        $this->assertSame('', $this->object->getDefaultValue());
    }

    public function testSetIsNullable()
    {
        $this->assertMethodChaining($this->object->setIsNullable(true), 'setIsNullable');
        $this->assertTrue($this->object->isNullable());
    }

    public function testCreateWhenPropertyIsNullableAndHasNotDefaultValue()
    {
        $this->object->setIsNullable(true);
        $this->assertNull($this->object->create(null));
        $this->assertSame('perfect jump', $this->object->create('perfect jump'));
    }

    public function testCreateWhenPropertyIsNotNullableAndHasDefaultValue()
    {
        $this->object->setDefaultValue('youthful scorpion');
        $this->assertSame('youthful scorpion', $this->object->create(null));
        $this->assertSame('perfect jump', $this->object->create('perfect jump'));
    }

    public function testCreateWhenPropertyIsNotNullableAndHasntDefaultValue()
    {
        $this->assertSame('', $this->object->create(null));
        $this->assertSame('perfect jump', $this->object->create('perfect jump'));
    }

    public function testExtractWhenPropertyIsNullableAndHasntDefaultValue()
    {
        $this->object->setIsNullable(true);
        $this->assertNull($this->object->extract(null));
        $this->assertSame('perfect jump', $this->object->extract('perfect jump'));
    }

    public function testExtractWhenPropertyIsNotNullableAndHasDefaultValue()
    {
        $this->object->setDefaultValue('youthful scorpion');
        $this->assertSame('youthful scorpion', $this->object->extract(null));
        $this->assertSame('perfect jump', $this->object->extract('perfect jump'));
    }

    public function testExtractWhenPropertyIsNotNullableAndHasntDefaultValue()
    {
        $this->assertSame('', $this->object->extract(null));
        $this->assertSame('perfect jump', $this->object->extract('perfect jump'));
    }

    public function testGetterNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->setGetterName('');
    }

    public function testSetGetGetterName()
    {
        $getter = 'getSomeData';
        $this->assertMethodChaining($this->object->setGetterName($getter), 'setGetterName');
        $this->assertSame($getter, $this->object->getGetterName());
    }

    public function testSetterNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->setSetterName('');
    }

    public function testSetGetSetterName()
    {
        $setter = 'setSomeData';
        $this->assertMethodChaining($this->object->setSetterName($setter), 'setSetterName');
        $this->assertSame($setter, $this->object->getSetterName());
    }

    public function testTargetPropertyNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->setTargetPropertyName('');
    }

    public function testSetGetTargetPropertyName()
    {
        $targetProperty = 'someProperty';
        $this->assertMethodChaining($this->object->setTargetPropertyName($targetProperty), 'setTargetPropertyName');
        $this->assertSame($targetProperty, $this->object->getTargetPropertyName());
    }

    public function testCannotBeNullableWhenHasDefaultValue()
    {
        $msg = 'Cannot set property to be nullable since it has default value';
        $this->setExpectedException('\BadMethodCallException', $msg);
        $this->object->setDefaultValue('some')
            ->setIsNullable(true);
    }

    public function testCannotSetDefaultValueWhenIsNullable()
    {
        $msg = 'Cannot set default value of property since it\'s nullable';
        $this->setExpectedException('\BadMethodCallException', $msg);
        $this->object->setIsNullable(true)
            ->setDefaultValue('some value');
    }

    /**
     * @depends testSetDefaultValue
     */
    public function testRemovingDefaultValue()
    {
        $this->object->setDefaultValue('some');
        $this->assertMethodChaining($this->object->removeDefaultValue(), 'removeDefaultValue');
        $this->assertFalse($this->object->hasDefaultValue());
    }
}
