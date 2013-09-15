<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\Builder\BasicSchemaLinker;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\MapTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Type\IntegerType;
use Wookieb\ZorroDataSchema\Schema\Type\MapType;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer32Outline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\MapOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class MapTypeBuilderTest extends ZorroUnit
{
    /**
     * @var MapTypeBuilder
     */
    protected $object;

    /**
     * @var BasicSchemaLinker
     */
    private $schemaLinker;

    protected function setUp()
    {
        $this->schemaLinker = new BasicSchemaLinker();
        $this->object = new MapTypeBuilder();
        $this->object->setSchemaLinker($this->schemaLinker);
        $this->schemaLinker->registerTypeBuilder($this->object);
    }

    public function testCanHandleOnlyMapOutlines()
    {
        $this->assertFalse($this->object->isAbleToGenerate(new StringOutline()));

        $outline = new MapOutline('map<string,int>', new StringOutline(), new Integer32Outline());
        $this->assertTrue($this->object->isAbleToGenerate($outline));
    }

    public function testExceptionWhenInvalidOutlineProvided()
    {
        $msg = 'Invalid type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generate(new StringOutline(), $this->getImplementationMock());
    }

    public function generateProvider()
    {
        return array(
            'simple map' => array(
                new MapOutline('map<string,int>', new StringOutline(), new Integer32Outline('int')),
                new MapType(new StringType(), new IntegerType(32))
            ),
            'nested maps' => array(
                new MapOutline('map<string,map<int,int>>', new StringOutline(),
                    new MapOutline('map<int,int>', new Integer32Outline(), new Integer32Outline())
                ),
                new MapType(new StringType(),
                    new MapType(new IntegerType(32), new IntegerType(32))
                )
            )
        );
    }

    /**
     * @dataProvider generateProvider
     */
    public function testGenerate(TypeOutlineInterface $outline, TypeInterface $expected)
    {
        $this->assertEquals($expected, $this->object->generate($outline, $this->getImplementationMock()));
    }
}
