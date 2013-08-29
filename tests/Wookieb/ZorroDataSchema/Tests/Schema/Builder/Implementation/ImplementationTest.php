<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation;

use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\GlobalClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\CamelCaseStyle;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StandardStyles;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ImplementationTest extends ZorroUnit
{
    /**
     * @var Implementation
     */
    protected $object;
    /**
     * @var ClassMapInterface
     */
    private $classMap;
    /**
     * @var GlobalClassTypeImplementation
     */
    private $globalClassOptions;

    protected function setUp()
    {
        $this->classMap = new ClassMap();

        $this->globalClassOptions = new GlobalClassTypeImplementation();
        $this->globalClassOptions->setAccessorsEnabled(false);
        $this->object = new Implementation($this->classMap, $this->globalClassOptions);
    }

    public function testRegisterClassTypeImplementation()
    {
        $implementation = new ClassTypeImplementation('helicopter');
        $implementation->setClassName('ConcreteXylophone');
        $this->assertMethodChaining($this->object->registerClassTypeImplementation($implementation), 'registerClassTypeImplementation');
        $this->assertSame($implementation, $this->object->getClassTypeImplementation('helicopter'));
    }

    /**
     * @depends testRegisterClassTypeImplementation
     */
    public function testLookingForClassNameForImplementationThatDoesNotContainClassName()
    {
        $this->classMap->registerClass('helicopter', 'ConcreteXylophone');

        $implementation = new ClassTypeImplementation('helicopter');
        $this->object->registerClassTypeImplementation($implementation);

        $this->assertSame('ConcreteXylophone', $this->object->getClassTypeImplementation('helicopter')->getClassName());
    }

    public function testDefaultClassImplementationIsCreatedWhenThereIsNoDefinedClassTypeImplementation()
    {
        $implementation = new ClassTypeImplementation('helicopter');
        $implementation->setClassName('ConcreteXylophone');
        $implementation->setAccessorsEnabled(false);

        $this->classMap->registerClass('helicopter', 'ConcreteXylophone');

        $result = $this->object->getClassTypeImplementation('helicopter');

        $this->assertEquals($implementation, $result);
    }

    /**
     * @depends testDefaultClassImplementationIsCreatedWhenThereIsNoDefinedClassTypeImplementation
     */
    public function testDefaultClassImplementationIsCreatedBasedOnGlobalClassTypeImplementation()
    {
        $this->globalClassOptions->setAccessorsEnabled(true)
            ->setAccessorsStyle(new CamelCaseStyle());

        $this->classMap->registerClass('helicopter', 'ConcreteXylophone');

        $implementation = new ClassTypeImplementation('helicopter');
        $implementation->setClassName('ConcreteXylophone')
            ->setAccessorsEnabled(true)
            ->setAccessorsStyle(new CamelCaseStyle());

        $this->assertEquals($implementation, $this->object->getClassTypeImplementation('helicopter'));
    }

    public function testSetGetClassMap() {
        $classMap = new ClassMap();
        $this->assertMethodChaining($this->object->setClassMap($classMap), 'setClassMap');
        $this->assertSame($classMap, $this->object->getClassMap());
    }

    public function testSetGetGlobalClassTypeImplementation() {
        $global = new GlobalClassTypeImplementation();
        $this->assertMethodChaining($this->object->setGlobalClassTypeImplementation($global), 'setGlobalClassTypeImplementation');
        $this->assertSame($global, $this->object->getGlobalClassImplementation());
    }
}
