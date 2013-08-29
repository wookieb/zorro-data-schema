<?php


namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;


use Wookieb\ZorroDataSchema\Schema\Type\DoubleType;

class DoubleTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testDoubleIsAnAliasForFloatType()
    {
        $type = new DoubleType();
        $this->assertInstanceOf('Wookieb\ZorroDataSchema\Schema\Type\FloatType', $type);
    }
}
