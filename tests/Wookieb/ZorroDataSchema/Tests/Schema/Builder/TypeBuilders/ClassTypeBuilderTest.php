<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\TypeBuilders;

use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMap;
use Wookieb\ZorroDataSchema\Schema\Builder\ClassMap\ClassMapInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ClassTypeImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Implementation;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\ImplementationInterface;
use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\PropertyImplementation;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;

use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\ClassTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Schema;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;
use Wookieb\ZorroDataSchema\Schema\Type\BooleanType;
use Wookieb\ZorroDataSchema\Schema\Type\ClassType;
use Wookieb\ZorroDataSchema\Schema\Type\PropertyDefinition;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ClassTypeBuilderTest extends ZorroUnit
{
    /**
     * @var ClassTypeBuilder
     */
    protected $object;

    /**
     * @var SchemaLinker
     */
    private $schemaLinker;

    /**
     * @var ImplementationInterface
     */
    private $implementation;

    /**
     * @var ClassMapInterface
     */
    private $classMap;

    /**
     * @var SchemaInterface
     */
    private $schema;

    protected function setUp()
    {
        $this->schema = new Schema();
        $this->schema->registerType('string', new StringType());

        $this->schemaLinker = new SchemaLinker();
        $this->schemaLinker->setSchema($this->schema);

        $this->classMap = new ClassMap();

        $this->implementation = new Implementation($this->classMap);

        $this->object = new ClassTypeBuilder();
        $this->object->setSchemaLinker($this->schemaLinker);
        $this->schemaLinker->registerTypeBuilder($this->object);
    }

    /**
     * @param string $name
     * @param TypeInterface $type
     * @return PropertyDefinition
     */
    private function property($name, TypeInterface $type = null)
    {
        return new PropertyDefinition($name, $type ? : new StringType());
    }

    /**
     * @param string $name
     * @return PropertyImplementation
     */
    private function propertyImplementation($name)
    {
        return new PropertyImplementation($name);
    }

    /**
     * @param string $name
     * @return PropertyOutline
     */
    private function propertyOutline($name)
    {
        return new PropertyOutline($name, new StringOutline());
    }

    public function testIsAbleToGenerateOnlyClassOutlines()
    {
        $this->assertTrue($this->object->isAbleToGenerate(new ClassOutline('vacuum')));
        $this->assertFalse($this->object->isAbleToGenerate(new BooleanOutline()));
    }

    public function testGenerateClass()
    {
        $parentClass = new ClassOutline('vacuumBadger');
        $outline = new ClassOutline('vacuum', array(), $parentClass);
        $outline
            ->addProperty(
                $this->propertyOutline('zoo')
                    ->setIsNullable(true)
            )
            ->addProperty(
                $this->propertyOutline('bridge')
                    ->setIsNullable(true)
            )
            ->addProperty(
                $this->propertyOutline('galley')
            )
            ->addProperty(
                $this->propertyOutline('granddaughter')
                    ->setDefaultValue('is young')
            );

        $this->classMap->registerClass('vacuum', 'VacuumZooBridge')
            ->registerClass('vacuumBadger', 'VacuumBadger');

        $classImplementation = new ClassTypeImplementation('vacuum');
        $classImplementation
            ->addPropertyImplementation(
                $this->propertyImplementation('zoo')
            )
            ->addPropertyImplementation(
                $this->propertyImplementation('bridge')
                    ->setSetter('setBridge')
                    ->setGetter('getBridge')
            )
            ->addPropertyImplementation(
                $this->propertyImplementation('galley')
                    ->setSetter('setGalley')
            )
            ->addPropertyImplementation(
                $this->propertyImplementation('granddaughter')
                    ->setGetter('getGranddaughter')
            );

        $this->implementation->registerClassTypeImplementation($classImplementation);

        $parentClassExpected = new ClassType('vacuumBadger', 'VacuumBadger');
        $expected = new ClassType('vacuum', 'VacuumZooBridge', $parentClassExpected);
        $expected
            ->addProperty(
                $this->property('zoo')
                    ->setIsNullable(true)
            )
            ->addProperty(
                $this->property('bridge')
                    ->setGetterName('getBridge')
                    ->setSetterName('setBridge')
                    ->setIsNullable(true)
            )
            ->addProperty(
                $this->property('galley')
                    ->setSetterName('setGalley')
            )
            ->addProperty(
                $this->property('granddaughter')
                    ->setDefaultValue('is young')
                    ->setGetterName('getGranddaughter')
            );

        $result = $this->object->generate($outline, $this->implementation);
        $this->assertEquals($expected, $result);
    }

    public function testExceptionWhenNoClassNameFound()
    {
        $outline = new ClassOutline('vacuumExtra');
        $msg = 'Cannot find class name for "vacuumExtra" type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generate($outline, $this->implementation);
    }

    public function testExceptionWhenInvalidTypeOutlineProvided()
    {
        $msg = 'Invalid type outline';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);
        $this->object->generate(new BooleanOutline(), $this->implementation);
    }

    public function testExceptionWhenParentClassOutlineWillBeTransformedToTypeThatIsNotInstanceOfAbstractClassType()
    {
        $msg = 'Parent type of class type must by an instance of class type';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeException', $msg);

        $parentClass = new ClassOutline('vacuumBadger');
        $outline = new ClassOutline('vacuum', array(), $parentClass);

        $this->schema->registerType('vacuumBadger', new BooleanType());

        $this->object->generate($outline, $this->implementation);
    }

    public function testProperHandlingOfCircularReferences()
    {
        $user = new ClassOutline('User');
        $admin = new ClassOutline('Admin');

        $user->addProperty(new PropertyOutline('admin', $admin));
        $admin->addProperty(new PropertyOutline('user', $user));

        $this->classMap->registerClass('User', 'User')
            ->registerClass('Admin', 'Admin');

        $userType = $this->object->generate($user, $this->implementation);

        $properties = $userType->getProperties();
        $adminType = $properties['admin']->getType();

        $properties = $adminType->getProperties();

        $this->assertSame($userType, $properties['user']->getType());
    }
}
