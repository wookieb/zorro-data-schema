<?php

namespace Wookieb\ZorroDataSchema\Tests\SchemaOutline\DynamicTypeOutline;

use Wookieb\ZorroDataSchema\SchemaOutline\BasicSchemaOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\DynamicTypeOutline\MapDynamicTypeOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\BooleanOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\Integer32Outline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\MapOutline;
use Wookieb\ZorroDataSchema\SchemaOutline\TypeOutline\StringOutline;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class MapDynamicTypeOutlineTest extends ZorroUnit
{
    /**
     * @var MapDynamicTypeOutline
     */
    protected $object;

    /**
     * @var BasicSchemaOutline
     */
    private $schemaOutline;

    protected function setUp()
    {
        $this->schemaOutline = new BasicSchemaOutline();
        $this->object = new MapDynamicTypeOutline($this->schemaOutline);
        $this->schemaOutline->addDynamicTypeOutline($this->object);
    }

    public function isAbleToGenerateProvider()
    {
        $testCases = array(
            'not a map' => array('collection<string>', false),
            'map without closing angle bracket' => array('map<string,int', false),
            'map without opening angle bracket' => array('map string,int>', false),
            'map with insufficient amount of defined types' => array('map<string>', false),
            'correct map with incorrect nested map' => array('map<map<, string>', true),
            'correct map' => array('map<string,int>', true),
            'correct map with nested map' => array('map<string,map<int,int>>', true),
            'correct map with nested map and nested map' => array(
                'map<int,map<int,map<string, bool>>>', true
            )
        );

        $additionalTestCases = array();
        foreach ($testCases as $name => &$testCase) {
            $additionalTestCases[$name.' with whitespaces'] = array(
                str_replace(array('<', ',', '>'), array('< ', ', ', ' >'), $testCase[0]),
                $testCase[1]
            );
        }
        return array_merge($testCases, $additionalTestCases);
    }

    /**
     * @dataProvider isAbleToGenerateProvider
     */
    public function testIsAbleToGenerate($name, $result)
    {
        $this->assertSame($result, $this->object->isAbleToGenerate($name));
    }

    public function generateProvider()
    {
        $testCases = array(
            'not a map' => array('collection<string>', false),
            'map without closing angle bracket' => array('map<string,int', false),
            'map without opening angle bracket' => array('map string,int>', false),
            'map with insufficient amount of defined types' => array('map<string>', false),
            'correct map with incorrect nested map' => array('map<map<, string>', false),
            'correct map' => array(
                'map<string,int>',
                new MapOutline('map<string,int>', new StringOutline(), new Integer32Outline('int'))
            ),
            'correct map with nested map' => array(
                'map<string,map<int,int>>',
                new MapOutline('map<string,map<int,int>>', new StringOutline(),
                    new MapOutline('map<int,int>', new Integer32Outline('int'), new Integer32Outline('int'))
                )
            ),
            'correct map with nested map and nested map' => array(
                'map<int,map<int,map<string, bool>>>',
                new MapOutline('map<int,map<int,map<string,bool>>>', new Integer32Outline('int'),
                    new MapOutline('map<int,map<string,bool>>', new Integer32Outline('int'),
                        new MapOutline('map<string,bool>', new StringOutline(), new BooleanOutline('bool'))
                    )
                )
            )
        );

        $additionalTestCases = array();
        foreach ($testCases as $name => &$testCase) {
            $additionalTestCases[$name.' with whitespaces'] = array(
                str_replace(array('<', ',', '>'), array('< ', ', ', ' >'), $testCase[0]),
                $testCase[1]
            );
        }
        return array_merge($testCases, $additionalTestCases);
    }

    /**
     * @dataProvider generateProvider
     */
    public function testGenerate($name, $expected)
    {
        if (!is_object($expected)) {
            $msg = vsprintf('Invalid name "%s" to generate map outline', $name);
            $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\UnableToGenerateTypeOutlineException', $msg);
        }
        $this->assertEquals($expected, $this->object->generate($name));
    }
}
