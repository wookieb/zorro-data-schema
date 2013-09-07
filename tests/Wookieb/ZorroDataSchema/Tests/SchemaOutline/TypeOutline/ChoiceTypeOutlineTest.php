<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\TypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ChoiceTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ChoiceTypeOutlineTest extends ZorroUnit
{
    /**
     * @var ChoiceTypeOutline
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ChoiceTypeOutline('someName');
    }

    public function testAddTypeOutline()
    {
        $outline = new StringOutline();
        $this->assertMethodChaining($this->object->addTypeOutline($outline), 'addTypeOutline');
        $this->assertEquals(array('string' => $outline), $this->object->getTypeOutlines());
    }

    public function testCannotUseOtherChoiceTypeOutline()
    {
        $outline = new ChoiceTypeOutline();

        $msg = 'Choice type cannot contain other Choice types';
        $this->setExpectedException('\InvalidArgumentException', $msg);

        $this->object->addTypeOutline($outline);
    }
}
