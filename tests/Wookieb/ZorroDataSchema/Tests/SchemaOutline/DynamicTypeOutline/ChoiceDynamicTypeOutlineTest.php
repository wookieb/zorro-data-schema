<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\DynamicTypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\BasicSchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\ChoiceDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ChoiceTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ChoiceDynamicTypeOutlineTest extends ZorroUnit
{
    /**
     * @var ChoiceDynamicTypeOutline
     */
    protected $object;

    /**
     * @var SchemaOutlineInterface
     */
    private $schemaOutline;

    protected function setUp()
    {
        $this->schemaOutline = new BasicSchemaOutline();
        $this->object = new ChoiceDynamicTypeOutline($this->schemaOutline);
    }

    public function isAbleToGenerateProvider()
    {
        return array(
            'just one type' => array('string', false),
            'one type with pipe on the end' => array('string | ', true),
            'two types with pipe on the end' => array('string | int16 |', true),
            'three types' => array('string | bool | bin', true)
        );
    }

    /**
     * @dataProvider isAbleToGenerateProvider
     */
    public function testIsAbleToGenerate($name, $result)
    {
        $this->assertSame($result, $this->object->isAbleToGenerate($name));
    }

    public function testGenerate()
    {
        $expected = new ChoiceTypeOutline('string|boolean');
        $expected->addTypeOutline(new StringOutline('string'))
            ->addTypeOutline(new BooleanOutline('boolean'));

        $result = $this->object->generate('string | boolean');
        $this->assertEquals($expected, $result);
    }

    public function testExceptionWhenAttemptToGenerateTypeThatUseLessThan2Files()
    {
        $msg = 'Choice type "string |" defines only one type. At least 2 required';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException', $msg);
        $this->object->generate('string |');
    }
}
