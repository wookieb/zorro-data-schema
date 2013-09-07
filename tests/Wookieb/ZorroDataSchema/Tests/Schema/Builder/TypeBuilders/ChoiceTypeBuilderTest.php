<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\BasicSchema;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\ChoiceTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Type\BooleanType;
use Wookieb\ZorroDataSchema\Schema\Type\ChoiceType;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ChoiceTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ChoiceTypeBuilderTest extends ZorroUnit
{
    /**
     * @var ChoiceTypeBuilder
     */
    protected $object;

    /**
     * @var SchemaLinker
     */
    private $schemaLinker;

    protected function setUp()
    {
        $this->schemaLinker = new SchemaLinker();
        $this->schemaLinker->setSchema(new BasicSchema());

        $this->object = new ChoiceTypeBuilder();
        $this->object->setSchemaLinker($this->schemaLinker);

        $this->schemaLinker->registerTypeBuilder($this->object);
    }

    /**
     * @return ChoiceTypeOutline
     */
    private function getExampleOutline()
    {
        $outline = new ChoiceTypeOutline();
        $outline->addTypeOutline(new StringOutline('string'))
            ->addTypeOutline(new BooleanOutline('boolean'));
        return $outline;
    }

    public function testIsAbleToGenerate()
    {
        $outline = $this->getExampleOutline();

        $this->assertTrue($this->object->isAbleToGenerate($outline));
        $this->assertFalse($this->object->isAbleToGenerate(new BooleanOutline('boolean')));
    }

    public function testGenerate()
    {
        $implementation = new Implementation();

        $expected = new ChoiceType();
        $expected->addType('string', new StringType());
        $expected->addType('boolean', new BooleanType());

        $outline = $this->getExampleOutline();

        $this->assertEquals($expected, $this->object->generate($outline, $implementation));
    }

    public function testExceptionWhenAttemptToGenerateTypeWithLessThan2TypesUsed()
    {
        $msg = 'Choice type must use at least 2 types';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);

        $implementation = new Implementation();

        $outline = new ChoiceTypeOutline();
        $outline->addTypeOutline(new BooleanOutline('bool'));

        $this->object->generate($outline, $implementation);
    }

    public function testExceptionWhenInvalidTypeOutlineProvided()
    {
        $msg = 'Invalid type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);

        $this->object->generate(new BooleanOutline(), new Implementation());
    }
}
