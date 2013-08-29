<?php
namespace Wookieb\ZorroDataSchema\Tests;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ZorroUnit extends \PHPUnit_Framework_TestCase
{

    protected $object;

    public function cannotBeEmpty($message = 'cannot be empty')
    {
        $this->setExpectedException('\InvalidArgumentException', $message);
    }

    public function assertMethodChaining($result, $methodName)
    {
        $this->assertSame($this->object, $result, 'Method chaining violation at "'.$methodName.'"');
    }

    public function notExists($message = 'not exist', $class = '\OutOfRangeException')
    {
        $this->setExpectedException($class, $message);
    }

    public function getImplementationMock()
    {
        return $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface');
    }
}
