<?php


namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation\Style;


use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\UnderscoreStyle;

class UnderscoreStyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UnderscoreStyle
     */
    private $object;

    protected function setUp()
    {
        $this->object = new UnderscoreStyle();
    }

    /**
     * @dataProvider setterNameWithPrefixProvider
     */
    public function testSetterNameWithPrefix($source, $target)
    {
        $this->assertSame($target, $this->object->generateSetterName($source));
    }

    public function setterNameWithPrefixProvider()
    {
        return array(
            'underscore' => array('some_property', 'set_some_property'),
            'camel case' => array('someProperty', 'set_some_property'),
            'pascal case' => array('Property', 'set_property')
        );
    }

    /**
     * @dataProvider setterNameWithoutPrefixProvider
     */
    public function testSetterNameWithoutPrefix($source, $target)
    {
        $this->object = new UnderscoreStyle(false);
        $this->assertSame($target, $this->object->generateSetterName($source));
    }

    public function setterNameWithoutPrefixProvider()
    {
        return array(
            'underscore' => array('some_property', 'some_property'),
            'camel case' => array('someProperty', 'some_property'),
            'pascal case' => array('Property', 'property')
        );
    }


    /**
     * @dataProvider getterNameWithPrefixProvider
     */
    public function testGetterNameWithPrefix($source, $target)
    {
        $this->assertSame($target, $this->object->generateGetterName($source));
    }

    public function getterNameWithPrefixProvider()
    {
        return array(
            'underscore' => array('some_property', 'get_some_property'),
            'camel case' => array('someProperty', 'get_some_property'),
            'pascal case' => array('Property', 'get_property')
        );
    }

    /**
     * @dataProvider getterNameWithoutPrefixProvider
     */
    public function testGetterNameWithoutPrefix($source, $target)
    {
        $this->object = new UnderscoreStyle(false);
        $this->assertSame($target, $this->object->generateGetterName($source));
    }

    public function getterNameWithoutPrefixProvider()
    {
        return array(
            'underscore' => array('some_property', 'some_property'),
            'camel case' => array('someProperty', 'some_property'),
            'pascal case' => array('Property', 'property')
        );
    }
}
