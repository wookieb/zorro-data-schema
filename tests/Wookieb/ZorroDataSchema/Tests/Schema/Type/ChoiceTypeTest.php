<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;

use Wookieb\ZorroDataSchema\Schema\Type\ClassType;
use Wookieb\ZorroDataSchema\Schema\Type\IntegerType;
use Wookieb\ZorroDataSchema\Schema\Type\PropertyDefinition;
use Wookieb\ZorroDataSchema\Schema\Type\ChoiceType;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ChoiceTypeTest extends ZorroUnit
{
    /**
     * @var ChoiceType
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ChoiceType();
        $this->object->addType('int64', new IntegerType());

        $classType = new ClassType('User', 'stdClass');
        $classType->addProperty(new PropertyDefinition('name', new StringType()));
        $classType->addProperty(new PropertyDefinition('lastname', new StringType()));

        $this->object->addType('User', $classType);
    }

    public function testAddType()
    {
        $this->object = new ChoiceType();

        $this->assertEquals(array(), $this->object->getTypes());

        $type = new StringType();
        $this->assertMethodChaining($this->object->addType('string', $type), 'addType');
        $this->assertEquals(array('string' => $type), $this->object->getTypes());
    }

    public function testTypeNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->addType('', new StringType());
    }

    public function testIsTargetType()
    {
        $this->assertFalse($this->object->isTargetType('foo'));
        $this->assertTrue($this->object->isTargetType(123));
        $this->assertTrue($this->object->isTargetType(new \stdClass));
    }

    public function testGetTypeCheck()
    {
        $this->assertFalse($this->object->getTypeCheck()->isValidType('foo'));
        $this->assertTrue($this->object->getTypeCheck()->isValidType(123));
        $this->assertTrue($this->object->getTypeCheck()->isValidType(new \stdClass));

        $this->assertSame('integers in range -9223372036854775808 to 9223372036854775807, instances of stdClass',
            $this->object->getTypeCheck()->getTypeDescription());
    }

    public function testExtractInteger()
    {
        $expected = array(
            '__type' => 'int64',
            'data' => 123
        );

        $this->assertEquals($expected, $this->object->extract(123));
    }

    public function testExtractUser()
    {
        $expected = array(
            '__type' => 'User',
            'data' => array(
                'name' => 'Łukasz',
                'lastname' => 'Kużyński'
            )
        );

        $user = new \stdClass();
        $user->name = 'Łukasz';
        $user->lastname = 'Kużyński';

        $this->assertEquals($expected, $this->object->extract($user));
    }

    public function testCreateInteger()
    {
        $data = array(
            '__type' => 'int64',
            'data' => '123'
        );
        $this->assertSame(123, $this->object->create($data));
    }

    public function testCreateUser()
    {
        $data = array(
            '__type' => 'User',
            'data' => array(
                'name' => 'Łukasz',
                'lastname' => 'Kużyński'
            )
        );

        $expected = new \stdClass();
        $expected->name = 'Łukasz';
        $expected->lastname = 'Kużyński';

        $this->assertEquals($expected, $this->object->create($data));
    }

    public function createAcceptOnlyArrayAndStdClassProvider()
    {
        $std = new \stdClass();
        $std->__type = 'User';

        $std->data = array(
            'name' => 'Łukasz',
            'lastname' => 'Kużyński'
        );

        return array(
            'array' => array(
                array('__type' => 'int64', 'data' => '12'),
                true
            ),
            'stdClass' => array($std, true),
            'string' => array('string', false),
            'some object' => array(new \ReflectionClass('\Exception'), false)
        );
    }

    /**
     * @dataProvider createAcceptOnlyArrayAndStdClassProvider
     */
    public function testCreateAcceptOnlyArrayAndStdClass($data, $result)
    {
        if (!$result) {
            $msg = 'Invalid data to create object. Only array and stdClass allowed';
            $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', $msg);
        }
        $this->object->create($data);
    }

    public function arrayForCreateMethodMustHaveSpecificStructureProvider()
    {
        return array(
            'must contains "__type"' => array(
                array('data' => 'data'),
                'Invalid array structure. No "__type" defined'
            ),
            'must contains "data"' => array(
                array('__type' => 'User'),
                'Invalid array structure. No "data" defined'
            )
        );
    }

    /**
     * @dataProvider arrayForCreateMethodMustHaveSpecificStructureProvider
     */
    public function testArrayForCreateMethodMustHaveSpecificStructure($data, $exceptionMessage = null)
    {
        if ($exceptionMessage) {
            $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', $exceptionMessage);
        }
        $this->object->create($data);
    }

    public function testExceptionWhenUnregisteredTypeUsed()
    {
        $msg = 'Cannot handle value of type "Admin" since there is no registered type with that name';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', $msg);

        $this->object->create(array(
            '__type' => 'Admin',
            'data' => array()
        ));
    }

    public function exceptionWhenThereIsNoTypeAbleToExtractValueProvider()
    {
        return array(
            'string value' => array(
                'string', 'No type found to handle string'
            ),
            'object' => array(
                new \ReflectionClass('\Exception'),
                'No type found to handle object of class "ReflectionClass"'
            )
        );
    }

    /**
     * @dataProvider exceptionWhenThereIsNoTypeAbleToExtractValueProvider
     */
    public function testExceptionWhenThereIsNoTypeAbleToExtractValue($value, $exceptionMessage = null)
    {
        if ($exceptionMessage) {
            $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', $exceptionMessage);
        }
        $this->object->extract($value);
    }
}
