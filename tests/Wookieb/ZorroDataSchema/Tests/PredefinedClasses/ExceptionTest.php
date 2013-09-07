<?php

namespace Wookieb\ZorroDataSchema\Tests\PredefinedClasses;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;
use Wookieb\ZorroDataSchema\Schema\Type\ClassType;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ExceptionTest extends ZorroUnit
{
    /**
     * @var ClassType
     */
    protected $object;

    protected function setUp()
    {
        $builder = new SchemaBuilder();
        $this->object = $builder->build()->getType('Exception');
    }

    public function extractingExceptionDataProvider()
    {
        return array(
            'only message' => array(
                new \Exception('Exception message'),
                array('message' => 'Exception message')
            ),
            'message with cause' => array(
                new \Exception('Exception message', null, new \Exception('Second exception message')),
                array(
                    'message' => 'Exception message',
                    'cause' => array(
                        'message' => 'Second exception message'
                    )
                )
            ),
            'message with cause and that cause has cause too' => array(
                new \Exception('Exception message', null,
                    new \Exception('Second exception message', null,
                        new \Exception('Third exception message')
                    )
                ),
                array(
                    'message' => 'Exception message',
                    'cause' => array(
                        'message' => 'Second exception message',
                        'cause' => array(
                            'message' => 'Third exception message'
                        )
                    )
                )
            )
        );
    }

    /**
     * @dataProvider extractingExceptionDataProvider
     */
    public function testExtractingExceptionData($exception, $expected)
    {
        $this->assertEquals($expected, $this->object->extract($exception));
    }

    public function creatingExceptionDataProvider()
    {
        return array(
            'only message' => array(
                array('message' => 'Exception message'),
                new \Exception('Exception message')
            ),
            'message with cause' => array(
                array(
                    'message' => 'Exception message',
                    'cause' => array(
                        'message' => 'Second exception message'
                    )
                ),
                new \Exception('Exception message', null, new \Exception('Second exception message'))
            ),
            'message with cause and that cause has cause too' => array(
                array(
                    'message' => 'Exception message',
                    'cause' => array(
                        'message' => 'Second exception message',
                        'cause' => array(
                            'message' => 'Third exception message'
                        )
                    )
                ),
                new \Exception('Exception message', null,
                    new \Exception('Second exception message', null,
                        new \Exception('Third exception message')
                    )
                )
            )
        );
    }

    /**
     * @dataProvider creatingExceptionDataProvider
     */
    public function testCreatingException($array, $exception)
    {
        $this->assertEqualsExceptions($exception, $this->object->create($array));
    }

    private function assertEqualsExceptions($expected, $actual)
    {
        $this->assertInstanceOf('\Exception', $expected, 'Expected object is not an Exception');
        $this->assertInstanceOf('\Exception', $actual, 'Actual object is not an Exception');

        /* @var \Exception $expected */
        /* @var \Exception $actual */
        $this->assertEquals($expected->getMessage(), $actual->getMessage());
        $this->assertEquals($expected->getCode(), $actual->getCode());

        if ($expected->getPrevious()) {
            $this->assertEqualsExceptions($expected->getPrevious(), $actual->getPrevious());
        }
    }
} 
