<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Wookieb\ZorroDataSchema\Schema\Type\EnumType;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class EnumTypeTest extends ZorroUnit
{
    /**
     * @var EnumType
     */
    protected $object;
    /**
     * @var array
     */
    private $options;

    protected function setUp()
    {
        $this->options = array(
            'YOUNG' => 1,
            'JEANS' => 2,
            'KEYBOARD' => 10
        );
        $this->object = new EnumType('recent panties', $this->options);
    }

    public function testNameCannotBeEmpty()
    {
        $this->cannotBeEmpty('Name of enum cannot be empty');
        new EnumType('', $this->options);
    }

    public function testAtLeast2OptionsRequired()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidTypeException', 'Invalid definition');
        try {
            new EnumType('recent panties', array('blushing' => 1));
        } catch (InvalidTypeException $e) {
            /* @var \Wookieb\ZorroDataSchema\Exception\InvalidTypeException $e */
            $errors = $e->getErrors();
            $this->assertContains('at least 2 options', reset($errors));
            throw $e;
        }
    }

    public function testOptionNameCannotBeEmpty()
    {
        $this->cannotBeEmpty('Option name cannot be empty');
        $this->object->addOption('', '1');
    }

    public function testAddingOption()
    {
        $this->assertMethodChaining($this->object->addOption('DRESS', 3), 'addOptions');
        $this->options['DRESS'] = 3;
        $this->assertEquals($this->options, $this->object->getOptions());
    }

    public function testCreate()
    {
        $this->assertSame(10, $this->object->create('KEYBOARD'));
        $this->assertSame(1, $this->object->create('YOUNG'));
        $this->assertSame(2, $this->object->create('JEANS'));

        $this->assertSame(10, $this->object->create(10));
        $this->assertSame(1, $this->object->create(1));
        $this->assertSame(2, $this->object->create(2));
    }

    public function testCreateAcceptOnlyScalarValues()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create enum value');
        $this->object->create(array());
    }

    public function testThrowsExceptionWhenInvalidValueProvidedToCreate()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'does not exist');
        $this->object->create('GRIZZLED');
    }

    public function testExtract()
    {
        $this->assertSame('KEYBOARD', $this->object->extract(10));
        $this->assertSame('YOUNG', $this->object->extract(1));
        $this->assertSame('JEANS', $this->object->extract(2));

        $this->assertSame('KEYBOARD', $this->object->extract('KEYBOARD'));
        $this->assertSame('YOUNG', $this->object->extract('YOUNG'));
        $this->assertSame('JEANS', $this->object->extract('JEANS'));
    }

    public function testExtractAcceptOnlyScalarValues()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to extract');
        $this->object->extract(array());
    }

    public function testThrowsExceptionWhenInvalidValueProvidedToExtract()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'does not exist');
        $this->object->extract(102);
    }

    public function testIsTargetType()
    {
        $this->assertTrue($this->object->isTargetType(1));
        $this->assertFalse($this->object->isTargetType(100));
        $this->assertFalse($this->object->isTargetType('JEANS'));
    }
}
