<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\TypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\MapOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class MapOutlineTest extends ZorroUnit
{
    /**
     * @var MapOutline
     */
    protected $object;
    private $keyTypeOutline;
    private $valueTypeOutline;

    protected function setUp()
    {
        $typeOutlineInterface = 'Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface';
        $this->keyTypeOutline = $this->getMockForAbstractClass($typeOutlineInterface);
        $this->valueTypeOutline = $this->getMockForAbstractClass($typeOutlineInterface);

        $this->object = new MapOutline('map<string,int>', $this->keyTypeOutline, $this->valueTypeOutline);
    }

    public function testGetKeyTypeOutline()
    {
        $this->assertSame($this->keyTypeOutline, $this->object->getKeyTypeOutline());
    }

    public function testGetValueTypeOutline()
    {
        $this->assertSame($this->valueTypeOutline, $this->object->getValueTypeOutline());
    }
}
