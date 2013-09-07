<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Wookieb\ZorroDataSchema\Loader\YamlLoader;
use Wookieb\ZorroDataSchema\Schema\BasicSchema;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;
use Wookieb\ZorroDataSchema\Schema\Schema;
use Wookieb\ZorroDataSchema\Schema\SchemaInterface;
use Wookieb\ZorroDataSchema\Schema\Type\AbstractClassType;
use Wookieb\ZorroDataSchema\Schema\Type\ClassType;
use Wookieb\ZorroDataSchema\Schema\Type\IntegerType;
use Wookieb\ZorroDataSchema\Schema\Type\PropertyDefinition;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\Schema\Type\TypeInterface;
use Wookieb\ZorroDataSchema\SchemaOutline\Builder\SchemaOutlineBuilder;
use Wookieb\ZorroDataSchema\SchemaOutline\SchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class SchemaBuilderTest extends ZorroUnit
{
    /**
     * @var SchemaBuilder
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new SchemaBuilder();
    }

    public function testSetGetImplementationBuilder()
    {
        $class = 'Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Builder\ImplementationBuilder';
        $implementationBuilder = $this->getMockBuilder($class)->disableOriginalConstructor()->getMock();

        $this->assertMethodChaining($this->object->setImplementationBuilder($implementationBuilder), 'setImplementationBuilder');
        $this->assertSame($implementationBuilder, $this->object->getImplementationBuilder());
    }

    public function testSetGetSchemaOutlineBuilder()
    {
        $class = 'Wookieb\ZorroDataSchema\SchemaOutline\Builder\SchemaOutlineBuilder';
        $schemaOutlineBuilder = $this->getMockBuilder($class)->disableOriginalConstructor()->getMock();
        $this->assertMethodChaining($this->object->setSchemaOutlineBuilder($schemaOutlineBuilder), 'setSchemaOutlineBuilder');
        $this->assertSame($schemaOutlineBuilder, $this->object->getSchemaOutlineBuilder());
    }

    public function testSetGetSchemaLinker()
    {
        $class = 'Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker';
        $schemaLinker = $this->getMockBuilder($class)->disableOriginalConstructor()->getMock();
        $this->assertMethodChaining($this->object->setSchemaLinker($schemaLinker), 'setSchemaLinker');
        $this->assertSame($schemaLinker, $this->object->getSchemaLinker());
    }

    public function testBuild()
    {

        $expected = new Schema();
        $this->prepareBuilder($expected);

        foreach (new BasicSchema() as $name => $type) {
            $expected->registerType($name, $type);
        }
        $this->registerPredefinedClassTypes($expected);
        $this->assertEquals($expected, $this->object->build());

        $expectedResources = array(
            new FileResource(TESTS_DIRECTORY.'/Resources/Schema/implementation.yml'),
            new FileResource(TESTS_DIRECTORY.'/Resources/Schema/schema.yml')
        );
        $this->assertEquals($expectedResources, $this->object->getResources());
    }

    private function registerPredefinedClassTypes(SchemaInterface $expectedSchema)
    {
        $classType = new ClassType('Exception', '\Exception');
        $classType->addProperty(new PropertyDefinition('message', new StringType()));
        $cause = new PropertyDefinition('cause', $classType, true);
        $cause->setTargetPropertyName('previous');
        $classType->addProperty($cause);

        $expectedSchema->registerType('Exception', $classType);
    }

    public function testBuildWithProvidedSchema()
    {
        $outline = new SchemaOutline();
        $outline->addTypeOutline(new StringOutline());
        $this->object->setSchemaOutlineBuilder(new SchemaOutlineBuilder($outline));

        $expected = new Schema();
        $expected->registerType('string', new StringType());

        $this->prepareBuilder($expected);

        $this->assertEquals($expected, $this->object->build());
    }

    private function prepareBuilder(SchemaInterface $schema)
    {
        $loader = new YamlLoader(new FileLocator(TESTS_DIRECTORY.'/Resources/Schema'));
        $this->assertMethodChaining($this->object->registerLoader($loader), 'registerLoader');
        $this->assertMethodChaining($this->object->loadSchema('schema.yml'), 'loadSchema');
        $this->assertMethodChaining($this->object->loadImplementation('implementation.yml'), 'loadImplementation');

        $expected = $schema;
        $expected
            ->registerType('User', $this->classType('User', 'User')
                ->addProperty(
                    $this->property('name', new StringType())
                )
                ->addProperty(
                    $this->property('lastname', new StringType())
                ))
            ->registerType('SuperUser', $this->classType('SuperUser', 'SuperUser', $expected->getType('User')));
    }

    /**
     * @param string $name
     * @param string $className
     * @param AbstractClassType $parentClass
     * @return ClassType
     */
    private function classType($name, $className, AbstractClassType $parentClass = null)
    {
        return new ClassType($name, $className, $parentClass);
    }

    /**
     * @param string $name
     * @param TypeInterface $type
     * @return PropertyDefinition
     */
    private function property($name, TypeInterface $type)
    {
        return new PropertyDefinition($name, $type);
    }

}
