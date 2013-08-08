<?php


namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation\Style;


use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\CamelCaseStyle;

class CamelCaseStyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CamelCaseStyle
     */
    private $object;

    protected function setUp()
    {
        $this->object = new CamelCaseStyle();
    }

    /**
     * @dataProvider setterNameWithPrefixProvider
     */
    public function testGenerateSetterNameWithPrefix($source, $target)
    {
        $this->assertSame($target, $this->object->generateSetterName($source));
    }

    public function setterNameWithPrefixProvider()
    {
        return array(
            'undescore style' => array('some_property', 'setSomeProperty'),
            'camel case' => array('someProperty', 'setSomeProperty'),
            'pascal case' => array('Property', 'setProperty')
        );
    }

    /**
     * @dataProvider setterNameWithoutPrefixProvider
     */
    public function testGenerateSetterNameWithoutPrefix($source, $target)
    {
        $this->object = new CamelCaseStyle(false);
        $this->assertSame($target, $this->object->generateSetterName($source));
    }

    public function setterNameWithoutPrefixProvider()
    {
        return array(
            'undescore style' => array('some_property', 'someProperty'),
            'camel case' => array('someProperty', 'someProperty'),
            'pascal case' => array('Property', 'property')
        );
    }

    /**
     * @dataProvider getterNameWithPrefixProvider
     */
    public function testGenerateGetterNameWithPrefix($source, $target)
    {
        $this->assertSame($target, $this->object->generateGetterName($source));
    }

    public function getterNameWithPrefixProvider()
    {
        return array(
            'undescore style' => array('some_property', 'getSomeProperty'),
            'camel case' => array('someProperty', 'getSomeProperty'),
            'pascal case' => array('Property', 'getProperty')
        );
    }

    /**
     * @dataProvider getterNameWithoutPrefixProvider
     */
    public function testGenerateGetterNameWithoutPrefix($source, $target)
    {
        $this->object = new CamelCaseStyle(false);
        $this->assertSame($target, $this->object->generateGetterName($source));
    }

    public function getterNameWithoutPrefixProvider()
    {
        return array(
            'undescore style' => array('some_property', 'someProperty'),
            'camel case' => array('someProperty', 'someProperty'),
            'pascal case' => array('Property', 'property')
        );
    }
}
