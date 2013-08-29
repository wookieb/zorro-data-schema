<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\Builder;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Wookieb\ZorroDataSchema\Loader\YamlLoader;
use Wookieb\ZorroDataSchema\SchemaOutline\Builder\SchemaOutlineBuilder;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\ClassOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\EnumOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\PropertyOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\TypeOutlineInterface;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;


class SchemaOutlineBuilderTest extends ZorroUnit
{
    /**
     * @var SchemaOutlineBuilder
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new SchemaOutlineBuilder();

        $loader = new YamlLoader(new FileLocator(TESTS_DIRECTORY.'/Resources/SchemaOutline/'));
        $this->object->registerLoader($loader);
    }

    private function property($name, TypeOutlineInterface $outline = null)
    {
        return new PropertyOutline($name, $outline ? : new StringOutline());
    }


    public function testBuild()
    {
        $this->object->load('outline.yml');
        $result = $this->object->build();

        $userStatus = new EnumOutline('UserStatus', array(
            'ACTIVE' => 1,
            'BANNED' => 2,
            'REMOVED' => 3,
            'INACTIVE' => 4
        ));

        $user = new ClassOutline('User', array(
            $this->property('name'),
            $this->property('lastname')->setIsNullable(true),
            $this->property('status', $userStatus)->setDefaultValue('INACTIVE')
        ));

        $superUser = new ClassOutline('SuperUser', array(
            $this->property('position')
        ), $user);

        $admin = new ClassOutline('Admin', array(), $superUser);

        $this->assertEquals($user, $result->getTypeOutline('User'));
        $this->assertEquals($admin, $result->getTypeOutline('Admin'));

        $expectedResources = array(
            new FileResource(TESTS_DIRECTORY.'/Resources/SchemaOutline/outline.yml'),
            new FileResource(TESTS_DIRECTORY.'/Resources/SchemaOutline/second_outline.yml')
        );
        $this->assertEquals($expectedResources, $this->object->getResources());
    }

    public function testExceptionWhenConfigurationStructureIsInvalid()
    {
        $msg = 'Invalid definition of schema outline in file "invalid_outline.yml"';
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\SchemaOutlineLoadingException', $msg);
        $this->object->load('invalid_outline.yml');
    }
}
