<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\DynamicTypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\HoistClassDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;


class HoistClassDynamicTypeOutlineTest extends ZorroUnit
{
    /**
     * @var HoistClassDynamicTypeOutline
     */
    protected $object;
    /**
     * @var SchemaOutline
     */
    private $schemaOutline;

    protected function setUp()
    {
        $this->schemaOutline = new SchemaOutline();
        $this->object = new HoistClassDynamicTypeOutline($this->schemaOutline);

        $this->schemaOutline->addTypeOutline(new StringOutline());
        $this->schemaOutline->addDynamicTypeOutline($this->object);
    }

    public function testIsAbleToGenerate()
    {
        $this->object->setClassesConfig(array(
            'User' => array()
        ));

        $this->assertTrue($this->object->isAbleToGenerate('User'));
        $this->assertFalse($this->object->isAbleToGenerate('Admin'));
    }

    private function property($name)
    {
        return new PropertyOutline($name, new StringOutline());
    }

    public function testGenerate()
    {
        $config = array(
            'User' => array(
                'properties' => array(
                    'name' => array(
                        'type' => 'string'
                    ),
                    'lastname' => array(
                        'type' => 'string',
                        'nullable' => true
                    ),
                    'nick' => array(
                        'type' => 'string',
                        'default' => 'NICKodem'
                    )
                )
            ),
            'SuperUser' => array(
                'extend' => 'User',
                'properties' => array(
                    'position' => array(
                        'type' => 'string'
                    )
                )
            )
        );

        $this->object->setClassesConfig($config);

        $parentClassOutline = new ClassOutline('User', array(
            $this->property('name'),
            $this->property('lastname')->setIsNullable(true),
            $this->property('nick')->setDefaultValue('NICKodem')
        ));

        $expected = new ClassOutline('SuperUser', array(
            $this->property('position')
        ), $parentClassOutline);

        $this->assertEquals($expected, $this->object->generate('SuperUser'));
    }

    public function testExceptionWhenParentClassIsNotClassOutline()
    {
        $msg = 'Parent class "string" must be a class outline instance';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException', $msg);

        $this->object->setClassesConfig(array(
            'User' => array(
                'extend' => 'string'
            )
        ));

        $this->object->generate('User');
    }

    public function testExceptionWhenInvalidNameProvided()
    {
        $msg = 'Class type "partner" does not exist';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException', $msg);
        $this->object->generate('partner');
    }

    public function testProperHandlingOfCircularReferences()
    {
        $this->object->setClassesConfig(array(
            'User' => array(
                'properties' => array(
                    'admin' => array(
                        'type' => 'Admin'
                    )
                )
            ),
            'Admin' => array(
                'properties' => array(
                    'user' => array(
                        'type' => 'User'
                    )
                )
            )
        ));

        $result = $this->object->generate('User');
        $this->assertInstanceOf('Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline', $result);


        /** @var ClassOutline $result */
        $properties = $result->getProperties();
        $admin = $properties['admin'];

        $properties = $admin->getType()->getProperties();
        $user = $properties['user'];

        $this->assertSame($result, $user->getType());
    }
}
