<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\EnumTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Type\EnumType;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\EnumOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;


class EnumTypeBuilderTest extends ZorroUnit
{

    /**
     * @var EnumTypeBuilder
     */
    protected $object;

    /**
     * @var EnumOutline
     */
    private $outline;

    protected function setUp()
    {
        $this->object = new EnumTypeBuilder();
        $this->outline = new EnumOutline('slip', array(
            'archaeology' => 1,
            'christmas' => 2
        ));

    }

    public function testIsAbleToHandleEnums()
    {
        $this->assertTrue($this->object->isAbleToGenerate($this->outline));
        $this->assertFalse($this->object->isAbleToGenerate(new BooleanOutline()));
    }

    public function testGenerate()
    {
        $expected = new EnumType('slip', array(
            'archaeology' => 1,
            'christmas' => 2
        ));

        $this->assertEquals($expected, $this->object->generate($this->outline, $this->getImplementationMock()));
    }

    public function testExceptionWhenInvalidOutlineProvided()
    {
        $msg = 'Invalid type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generate(new BooleanOutline(), $this->getImplementationMock());
    }
}
