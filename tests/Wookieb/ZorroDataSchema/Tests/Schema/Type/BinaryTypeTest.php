<?php
namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;
use Wookieb\ZorroDataSchema\Schema\Type\BinaryType;

class BinaryTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBinaryTypeIsJustAnAliasForString()
    {
        $this->assertInstanceOf('Wookieb\ZorroDataSchema\Schema\Type\StringType', new BinaryType());
    }
}
