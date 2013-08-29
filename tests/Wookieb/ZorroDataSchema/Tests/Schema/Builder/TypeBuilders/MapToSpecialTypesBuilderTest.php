<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\MapToSpecialTypesBuilder;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class MapToSpecialTypesBuilderTest extends ZorroUnit
{
    /**
     * @var MapToSpecialTypesBuilder
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new MapToSpecialTypesBuilder();
    }

    public function testMapToSpecialType()
    {
        $outline = new StringOutline();
        $this->assertFalse($this->object->isAbleToGenerate($outline));

        $targetTypeClass = 'Wookieb\ZorroDataSchema\Schema\Type\BooleanType';
        $this->assertMethodChaining($this->object->mapToSpecialType('string', $targetTypeClass), 'mapToSpecialType');
        $this->assertTrue($this->object->isAbleToGenerate($outline));
    }

    /**
     * @depends testMapToSpecialType
     */
    public function testGenerate()
    {
        $outline = new StringOutline();
        $targetTypeClass = 'Wookieb\ZorroDataSchema\Schema\Type\BooleanType';
        $this->object->mapToSpecialType('string', $targetTypeClass);

        $result = $this->object->generate($outline, $this->getImplementationMock());
        $this->assertInstanceOf($targetTypeClass, $result);
    }

    public function testExceptionWhenThereIsNoMappedTypeForOutline()
    {
        $msg = 'There is no mapped type class for type "string"';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generate(new StringOutline(), $this->getImplementationMock());
    }
}
