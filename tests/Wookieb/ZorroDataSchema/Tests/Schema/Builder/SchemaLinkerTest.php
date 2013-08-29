<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder;

use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\BasicTypesBuilder;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\MapToSpecialTypesBuilder;
use Wookieb\ZorroDataSchema\Schema\Schema;
use Wookieb\ZorroDataSchema\Schema\Type\BinaryType;
use Wookieb\ZorroDataSchema\Schema\Type\DateType;
use Wookieb\ZorroDataSchema\Schema\Type\FloatType;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\FloatOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class SchemaLinkerTest extends ZorroUnit
{
    /**
     * @var SchemaLinker
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new SchemaLinker();
    }

    public function testRegisterTypeBuilder()
    {
        $typeBuilder = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\TypeBuilderInterface');
        $this->assertMethodChaining($this->object->registerTypeBuilder($typeBuilder), 'registerTypeBuilder');
    }

    /**
     * @depends testRegisterTypeBuilder
     */
    public function testGeneratorsPriority()
    {
        $implementation = $this->getImplementationMock();

        $specialBuilder = new MapToSpecialTypesBuilder();
        $specialBuilder->mapToSpecialType('string', 'Wookieb\ZorroDataSchema\Schema\Type\BooleanType');

        $this->object->registerTypeBuilder(new BasicTypesBuilder())
            ->registerTypeBuilder($specialBuilder, 100);

        $this->assertInstanceOf('Wookieb\ZorroDataSchema\Schema\Type\FloatType',
            $this->object->generateType(new FloatOutline(), $implementation));

        $this->assertInstanceOf('Wookieb\ZorroDataSchema\Schema\Type\BooleanType',
            $this->object->generateType(new StringOutline(), $implementation));
    }

    public function testTypesAlreadyDefinedInSchemaDoNotNeedToBeGenerated()
    {
        $type = new StringType();
        $schema = new Schema();
        $schema->registerType('string', $type);

        $this->assertMethodChaining($this->object->setSchema($schema), 'setSchema');
        $this->assertSame($type, $this->object->generateType(new StringOutline(), $this->getImplementationMock()));
    }

    public function testExceptionWhenThereIsNoGeneratorAbleToHandleProvidedOutline()
    {
        $msg = 'There is no type builder able to generate "string" type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generateType(new StringOutline(), $this->getImplementationMock());
    }

    public function testLinkingProcessOnDefaultSchema()
    {
        list($outline, $implementation) = $this->prepareLinker();
        /** @var SchemaOutline $outline */
        /** @var Implementation $implementation */

        $expectedSchema = new Schema();
        $expectedSchema->registerType('string', new StringType())
            ->registerType('boolean', new FloatType())
            ->registerType('date', new DateType());

        $schema = new Schema();
        $schema->registerType('date', new DateType());

        $this->object->setSchema($schema);
        $result = $this->object->build($outline, $implementation);
        $this->assertEquals($expectedSchema, $result);
    }

    public function testLinkingProcessOnEmptySchema()
    {
        list($outline, $implementation) = $this->prepareLinker();
        /** @var SchemaOutline $outline */
        /** @var Implementation $implementation */

        $expectedSchema = new Schema();
        $expectedSchema->registerType('string', new StringType())
            ->registerType('boolean', new FloatType());

        $result = $this->object->build($outline, $implementation);
        $this->assertEquals($expectedSchema, $result);
    }

    public function testLinkingProcessOnProvidedSchema()
    {
        list($outline, $implementation) = $this->prepareLinker();
        /** @var SchemaOutline $outline */
        /** @var Implementation $implementation */

        $expectedSchema = new Schema();
        $expectedSchema->registerType('string', new StringType())
            ->registerType('boolean', new FloatType())
            ->registerType('binary', new BinaryType());

        $schema = new Schema();
        $schema->registerType('binary', new BinaryType());

        $result = $this->object->build($outline, $implementation, $schema);
        $this->assertEquals($expectedSchema, $result);
    }

    public function testLinkingProcessOnSchemaWithAlreadyDefinedTypes()
    {
        list($outline, $implementation) = $this->prepareLinker(false);
        /** @var SchemaOutline $outline */
        /** @var Implementation $implementation */

        $expectedSchema = new Schema();
        $expectedSchema->registerType('string', new StringType())
            ->registerType('boolean', new FloatType())
            ->registerType('binary', new BinaryType());

        $this->object->setSchema($expectedSchema);
        $result = $this->object->build($outline, $implementation);
        $this->assertEquals($expectedSchema, $result);
    }

    private function prepareLinker($useMockGenerator = true)
    {
        $outline = new SchemaOutline();
        $outline->addTypeOutline(new StringOutline())
            ->addTypeOutline(new BooleanOutline());

        $implementation = new Implementation();

        $this->object->registerTypeBuilder(new BasicTypesBuilder());
        if ($useMockGenerator) {
            $mockTypeBuilder = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\TypeBuilderInterface');

            $mockTypeBuilder->expects($this->atLeastOnce())
                ->method('isAbleToGenerate')
                ->will($this->returnCallback(function ($value) {
                    return $value instanceof BooleanOutline;
                }));

            $mockTypeBuilder->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(new BooleanOutline()), $this->equalTo($implementation))
                ->will($this->returnValue(new FloatType()));

            $this->object->registerTypeBuilder($mockTypeBuilder, 100);
        }
        return array($outline, $implementation);
    }
}
