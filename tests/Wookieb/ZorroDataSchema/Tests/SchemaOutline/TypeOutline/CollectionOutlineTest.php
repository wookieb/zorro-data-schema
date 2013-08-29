<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\TypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\CollectionOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class CollectionOutlineTest extends ZorroUnit
{
    /**
     * @var CollectionOutline
     */
    protected $object;
    private $type;

    protected function setUp()
    {
        $this->type = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface');
        $this->object = new CollectionOutline('racing', $this->type);
    }

    public function testGetName()
    {
        $this->assertSame('racing', $this->object->getName());
    }

    public function testGetType()
    {
        $this->assertSame($this->type, $this->object->getElementsType());
    }
}
