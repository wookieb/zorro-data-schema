<?php

namespace Wookieb\ZorroDataSchema\Tests\Loader;

use Symfony\Component\Config\FileLocator;
use Wookieb\ZorroDataSchema\Loader\LoadingContext;
use Wookieb\ZorroDataSchema\Loader\YamlLoader;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class YamlLoaderTest extends ZorroUnit
{
    /**
     * @var YamlLoader
     */
    protected $object;

    protected function setUp()
    {
        $locator = new FileLocator(TESTS_DIRECTORY.'/Resources/Loader');
        $this->object = new YamlLoader($locator);
        $this->object->setLoadingContext(new LoadingContext());
    }

    public function testLoad()
    {
        $expected = array(
            array(
                'classes' => array(
                    'Yak' => array(
                        'class' => 'TartAnime'
                    )
                )
            ),
            array(
                'classes' => array(
                    'Captain' => array(
                        'class' => 'HomelyGear'
                    )
                )
            )
        );

        $this->assertEquals($expected, $this->object->load('main.yml'));
    }

    public function testExceptionWhenFileDoesNotExist()
    {
        $this->setExpectedException('\InvalidArgumentException', 'The file "test.yml" does not exist');
        $this->object->load('test.yml');
    }

    public function testReturnsArrayEvenWhenFileIsEmpty()
    {
        $this->assertEquals(array(), $this->object->load('empty.yml'));
    }
}
