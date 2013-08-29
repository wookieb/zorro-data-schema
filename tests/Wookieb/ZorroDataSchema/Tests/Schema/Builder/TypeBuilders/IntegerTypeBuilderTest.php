<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\IntegerTypeBuilder;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ByteOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer16Outline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer32Outline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer64Outline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;


class IntegerTypeBuilderTest extends ZorroUnit
{
    /**
     * @var IntegerTypeBuilder
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new IntegerTypeBuilder();
    }

    public function integers()
    {
        return array(
            'byte' => array(new ByteOutline()),
            'integer 16' => array(new Integer16Outline()),
            'integer 32' => array(new Integer32Outline()),
            'integer 64' => array(new Integer64Outline())
        );
    }

    /**
     * @dataProvider integers
     */
    public function testConvertToIntegerType($outline)
    {
        $implementation = $this->getImplementationMock();
        $this->assertTrue($this->object->isAbleToGenerate($outline));
        $result = $this->object->generate($outline, $implementation);
        $this->assertInstanceOf('Wookieb\ZorroDataSchema\Schema\Type\IntegerType', $result);
    }

    public function testExceptionWhenInvalidOutlineProvided()
    {
        $msg = 'Cannot match num of bites';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generate(new BooleanOutline(), $this->getImplementationMock());
    }

}
