<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\TypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\EnumOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class EnumOutlineTest extends ZorroUnit
{
    /**
     * @var EnumOutline
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new EnumOutline('pimple', array(
            'super' => 1,
            'large' => 2
        ));
    }

    public function testGetName()
    {
        $this->assertSame('pimple', $this->object->getName());
    }

    public function testGetOptions()
    {
        $expected = array(
            'super' => 1,
            'large' => 2
        );
        $this->assertEquals($expected, $this->object->getOptions());
    }

    public function testOptionNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        new EnumOutline('pimple', array('' => 1, 'super' => 2));
    }

    public function testRequireAtLeast2Options() {
        $this->setExpectedException('\InvalidArgumentException', 'at least 2 options');
        new EnumOutline('pimple', array());
    }
}
