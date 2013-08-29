<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation\Builder;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Wookieb\ZorroDataSchema\Loader\YamlLoader;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Builder\ImplementationBuilder;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\GlobalClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\PropertyImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\CamelCaseStyle;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\UnderscoreStyle;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ImplementationBuilderTest extends ZorroUnit
{
    /**
     * @var ImplementationBuilder
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ImplementationBuilder();

        $locator = new FileLocator(TESTS_DIRECTORY.'/Resources/Implementation');
        $loader = new YamlLoader($locator);
        $this->object->registerLoader($loader);
    }

    /**
     * @param string $name
     * @return ClassTypeImplementation
     */
    private function classImplementation($name)
    {
        return new ClassTypeImplementation($name);
    }

    /**
     * @param string $name
     * @return PropertyImplementation
     */
    private function propertyImplementation($name)
    {
        return new PropertyImplementation($name);
    }

    public function testBuild()
    {
        $this->object->load('implementation.yml');

        $classMap = new ClassMap();
        $classMap->registerNamespace('Wookieb\ZorroDataSchema\Tests\Resources\Implementation\Entity')
            ->registerNamespace('Wookieb\ZorroDataSchema\Tests\Resources\Implementation\SecondEntity');

        $globalOptions = new GlobalClassTypeImplementation();
        $globalOptions->setAccessorsStyle(new CamelCaseStyle())
            ->setAccessorsEnabled(true);
        $implementation = new Implementation($classMap, $globalOptions);
        $implementation
            ->registerClassTypeImplementation(
                $this->classImplementation('User')
                    ->setAccessorsEnabled(false)
                    ->setAccessorsStyle(new CamelCaseStyle())
                    ->addPropertyImplementation(
                        $this->propertyImplementation('name')
                            ->setSetter('setLogin')
                    )
                    ->addPropertyImplementation(
                        $this->propertyImplementation('lastname')
                            ->setTargetPropertyName('surname')
                    )
                    ->addPropertyImplementation(
                        $this->propertyImplementation('nickname')
                            ->setGetter('getNick')
                    )
                    ->setAccessorsEnabled(false)
            )
            ->registerClassTypeImplementation(
                $this->classImplementation('Admin')
                    ->setAccessorsEnabled(true)
                    ->setAccessorsStyle(new UnderscoreStyle())
                    ->setClassName('Wookieb\ZorroDataSchema\Tests\Resources\Implementation\Admin')
                    ->addPropertyImplementation(
                        $this->propertyImplementation('masterOfDisaster')
                            ->setTargetPropertyName('yesHeIs')
                            ->setSetter('set_yes_he_is')
                            ->setGetter('get_yes_he_is')
                    )
            )
            ->registerClassTypeImplementation(
                $this->classImplementation('SuperUser')
                    ->setAccessorsEnabled(true)
                    ->setAccessorsStyle(new CamelCaseStyle())
                    ->addPropertyImplementation(
                        $this->propertyImplementation('position')
                            ->setSetter('setPositionName')
                            ->setGetter('getPosition')
                    )
                    ->addPropertyImplementation(
                        $this->propertyImplementation('platform')
                            ->setGetter('getSuperUserPlatform')
                            ->setSetter('setPlatform')
                    )
                    ->addPropertyImplementation(
                        $this->propertyImplementation('system')
                            ->setSetter('setOperatingSystem')
                            ->setGetter('getOperatingSystem')
                            ->setTargetPropertyName('operatingSystem')
                    )
            );

        $this->assertEquals($implementation, $this->object->build());
        $expectedResources = array(
            new FileResource(TESTS_DIRECTORY.'/Resources/Implementation/implementation.yml'),
            new FileResource(TESTS_DIRECTORY.'/Resources/Implementation/second_implementation.yml')
        );
        $this->assertEquals($expectedResources, $this->object->getResources());
    }
}
