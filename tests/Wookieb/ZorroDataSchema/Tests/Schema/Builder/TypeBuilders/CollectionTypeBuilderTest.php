<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\CollectionTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Type\BooleanType;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class CollectionTypeBuilderTest extends ZorroUnit
{
    /**
     * @var CollectionTypeBuilder
     */
    protected $object;

    /**
     * @var SchemaLinker|\PHPUnit_Framework_MockObject_MockObject
     */
    private $schemaLinker;

    protected function setUp()
    {
        $this->schemaLinker = $this->getMock('Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker');

        $this->object = new CollectionTypeBuilder();
        $this->object->setSchemaLinker($this->schemaLinker);
    }

    public function testConvert()
    {
        $implementation = $this->getImplementationMock();
        $elementsTypeOutline = new BooleanOutline();

        $this->schemaLinker->expects($this->once())
            ->method('generateType')
            ->with($this->equalTo($elementsTypeOutline), $this->equalTo($implementation))
            ->will($this->returnValue(new BooleanType()));

        $typeOutline = new CollectionOutline('collection<boolean>', $elementsTypeOutline);

        $this->assertTrue($this->object->isAbleToGenerate($typeOutline));
        $this->assertInstanceOf('Wookieb\ZorroDataSchema\Schema\Type\CollectionType',
            $this->object->generate($typeOutline, $implementation));
    }

    public function testExceptionWhenInvalidOutlineProvided() {
        $msg = 'Invalid type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generate(new BooleanOutline(), $this->getImplementationMock());
    }
}
