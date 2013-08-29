<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\TypeOutline;
require_once __DIR__.'/../../Resources/SchemaOutline/SimpleTypeOutline.php';
require_once __DIR__.'/../../Resources/SchemaOutline/TypeOutlineWithDefaultName.php';
use Wookieb\Tests\Resources\SchemaOutline\SimpleTypeOutline;
use Wookieb\Tests\Resources\SchemaOutline\TypeOutlineWithDefaultName;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;


class AbstractTypeOutlineTest extends ZorroUnit
{
    public function testGetName()
    {
        $name = 'WarningZone';
        $object = new SimpleTypeOutline($name);
        $this->assertSame($name, $object->getName());
    }

    public function testNameCannotBeEmpty() {
        $this->cannotBeEmpty();
        new SimpleTypeOutline('');
    }

    public function testDefaultName() {
        $object = new TypeOutlineWithDefaultName();
        $this->assertSame('DefaultToilet', $object->getName());
    }
}
