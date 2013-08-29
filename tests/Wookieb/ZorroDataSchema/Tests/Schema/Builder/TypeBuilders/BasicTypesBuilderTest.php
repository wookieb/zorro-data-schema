<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\BasicTypesBuilder;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer64Outline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;

class BasicTypesBuilderTest extends ZorroUnit
{

    /**
     * @var BasicTypesBuilder
     */
    protected $object;
    /**
     * @var ImplementationInterface
     */
    private $implementation;

    protected function setUp()
    {
        $this->object = new BasicTypesBuilder();
        $this->implementation = $this->getImplementationMock();
    }

    public function testBuild()
    {
        $typeOutline = new BooleanOutline();
        $this->assertTrue($this->object->isAbleToGenerate($typeOutline));
        $this->assertInstanceOf('Wookieb\ZorroDataSchema\Schema\Type\BooleanType',
            $this->object->generate($typeOutline, $this->implementation));
    }

    public function testExceptionWhenInvalidOutlineProvided()
    {
        $msg = 'No basic type defined for "integer" type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);

        $outline = new Integer64Outline('integer');
        $this->assertFalse($this->object->isAbleToGenerate($outline));
        $this->object->generate($outline, $this->implementation);
    }
}
