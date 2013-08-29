<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline;

use Wookieb\ZorroDataSchema\Exception\TypeOutlineNotFoundException;
use Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\DynamicTypeOutlineInterface;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class SchemaOutlineTest extends ZorroUnit
{
    /**
     * @var SchemaOutline
     */
    protected $object;

    /**
     * @var TypeOutlineInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $type;

    protected function setUp()
    {
        $this->type = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface');
        $this->type->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('OrnatePatient'));

        $this->object = new SchemaOutline();
    }

    /**
     * @param string $name
     * @param TypeOutlineInterface $typeOutline
     *
     * @return DynamicTypeOutlineInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getDynamicTypeOutlineMock($name = null, TypeOutlineInterface $typeOutline = null)
    {
        $dynamicType = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\DynamicTypeOutlineInterface');
        if ($name) {
            $dynamicType->expects($this->atLeastOnce())
                ->method('isAbleToGenerate')
                ->with($name)
                ->will($this->returnValue(true));
        }

        if ($typeOutline) {
            $dynamicType->expects($this->atLeastOnce())
                ->method('generate')
                ->with($name)
                ->will($this->returnValue($typeOutline));
        }

        return $dynamicType;
    }

    public function testAddType()
    {
        $this->assertMethodChaining($this->object->addTypeOutline($this->type), 'addTypeOutline');
        $this->assertSame($this->type, $this->object->getTypeOutline('OrnatePatient'));
        $this->assertTrue($this->object->hasTypeOutline('OrnatePatient'));

        $result = iterator_to_array($this->object);
        $this->assertSame($this->type, $result['OrnatePatient']);
    }

    public function testAddDynamicType()
    {
        $dynamicType = $this->getDynamicTypeOutlineMock();
        $this->assertMethodChaining($this->object->addDynamicTypeOutline($dynamicType), 'addDynamicTypeOutline');
    }

    /**
     * @depends testAddDynamicType
     */
    public function testGenerationOfTypes()
    {
        $dynamicType = $this->getDynamicTypeOutlineMock('ThankfulOx', $this->type);
        $this->object->addDynamicTypeOutline($dynamicType);

        $result = $this->object->getTypeOutline('ThankfulOx');
        $this->assertSame($this->type, $result);
    }

    /**
     * @depends testAddDynamicType
     */
    public function testInvalidGenerationOfType()
    {
        $dynamicType = $this->getDynamicTypeOutlineMock('ThankfulOx');
        $this->object->addDynamicTypeOutline($dynamicType);

        $exception = new UnableToGenerateTypeOutlineException('LastingLevel');
        $dynamicType->expects($this->once())
            ->method('generate')
            ->with('ThankfulOx')
            ->will($this->throwException($exception));

        $msg = 'Type outline "ThankfulOx" not found';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\TypeOutlineNotFoundException', $msg);
        try {
            $this->object->getTypeOutline('ThankfulOx');
        } catch (TypeOutlineNotFoundException $e) {
            $this->assertEquals($exception, $e->getPrevious());
            throw $e;
        }
    }

    public function testExceptionWhenTypeDoesNotExist()
    {
        $msg = 'Type outline "ThankfulOx" not found';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\TypeOutlineNotFoundException', $msg);
        $this->object->getTypeOutline('ThankfulOx');
    }
}
