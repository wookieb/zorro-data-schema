<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\ClassMap;

require_once __DIR__.'/../../../Resources/ClassMap/Lovable/KeenDuck.php';
require_once __DIR__.'/../../../Resources/ClassMap/ShamelessPvc.php';
require_once __DIR__.'/../../../Resources/SecondClassMap/ShamelessPvc.php';

use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ClassMapTest extends ZorroUnit
{
    /**
     * @var ClassMap
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ClassMap();
        $this->object->registerNamespace('Wookieb\ZorroDataSchema\Tests\Resources\ClassMap');
        $this->object->registerNamespace('Wookieb\ZorroDataSchema\Tests\Resources\SecondClassMap');
    }

    public function testRegisterClass()
    {
        $this->assertMethodChaining($this->object->registerClass('vibrant_exclamation', 'VibrantExclamation'), 'registerClass');
        $this->assertSame('VibrantExclamation', $this->object->getClass('vibrant_exclamation'));
    }

    public function testRegisterNamespace()
    {
        $this->assertMethodChaining($this->object->registerNamespace('SomeNamespace'), 'registerNamespace');
    }

    /**
     * @depends testRegisterNamespace
     */
    public function testLookupForClassInRegisteredNamespaces()
    {
        $prefix = 'Wookieb\ZorroDataSchema\Tests\Resources\ClassMap\\';

        $msg = 'order of registered namespaces should be important';
        $this->assertSame($prefix.'ShamelessPvc', $this->object->getClass('ShamelessPvc'), $msg);

        $msg = 'should replace dot in type name to namespace separator';
        $this->assertSame($prefix.'Lovable\KeenDuck', $this->object->getClass('Lovable.KeenDuck'), $msg);
    }

    /**
     * @depends testRegisterClass
     */
    public function testExplicitlyRegisteredClassesAlwaysWin()
    {
        $this->object->registerClass('ShamelessPvc', 'LoathsomeOwl');
        $this->assertSame('LoathsomeOwl', $this->object->getClass('ShamelessPvc'));
    }

    public function testExceptionWhenClassCannotBeFound() {
        $msg = 'No class name defined for type "DentalGuitar"';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\ClassNotFoundException', $msg);
        $this->object->getClass('DentalGuitar');
    }
}
