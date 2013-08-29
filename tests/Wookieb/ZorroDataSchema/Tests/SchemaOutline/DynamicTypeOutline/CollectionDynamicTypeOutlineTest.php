<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\DynamicTypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\CollectionDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class CollectionDynamicTypeOutlineTest extends ZorroUnit
{
    /**
     * @var CollectionDynamicTypeOutline
     */
    protected $object;

    /**
     * @var SchemaOutline
     */
    private $schemaOutline;

    protected function setUp()
    {
        $this->schemaOutline = new SchemaOutline();
        $this->object = new CollectionDynamicTypeOutline($this->schemaOutline);
    }

    /**
     * @test is able to generates types with "collection" prefix
     */
    public function testIsAbleToGenerateTypesWithSomePrefixes()
    {
        $this->assertTrue($this->object->isAbleToGenerate('collection<certification>'));
        $this->assertFalse($this->object->isAbleToGenerate('collection<>'));
        $this->assertFalse($this->object->isAbleToGenerate('utter<certification>'));
    }

    public function testGenerate()
    {
        $this->schemaOutline->addTypeOutline(new BooleanOutline('bool'));
        $name = 'collection<bool>';
        $expected = new CollectionOutline($name, new BooleanOutline('bool'));
        $this->assertEquals($expected, $this->object->generate($name));
    }

    public function testExceptionWhenInvalidNameProvided()
    {
        $msg = 'Invalid name to generate collection outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException', $msg);
        $this->object->generate('titanic');
    }
}
