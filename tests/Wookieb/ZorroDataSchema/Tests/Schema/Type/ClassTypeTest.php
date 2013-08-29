<?php
namespace Wookieb\ZorroDataSchema\Tests\Schema\Type;
require_once __DIR__.'/../../Resources/IntentionalQuiver.php';
require_once __DIR__.'/../../Resources/PointedZebra.php';
require_once __DIR__.'/../../Resources/QualifiedGerman.php';

use Wookieb\Tests\Resources\IntentionalQuiver;
use Wookieb\Tests\Resources\PointedZebra;
use Wookieb\Tests\Resources\QualifiedGerman;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;
use Wookieb\ZorroDataSchema\Schema\Type\BooleanType;
use Wookieb\ZorroDataSchema\Schema\Type\ClassType;
use Wookieb\ZorroDataSchema\Schema\Type\IntegerType;
use Wookieb\ZorroDataSchema\Schema\Type\PropertyDefinition;
use Wookieb\ZorroDataSchema\Schema\Type\StringType;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class ClassTypeTest extends ZorroUnit
{
    /**
     * @var ClassType
     */
    private $pointedZebra;
    /**
     * @var ClassType
     */
    private $qualifiedGerman;
    /**
     * @var ClassType
     */
    private $intentionalQuiver;

    public function setUp()
    {
        $this->qualifiedGerman = new ClassType('QualifiedGerman', 'Wookieb\Tests\Resources\QualifiedGerman');
        $this->qualifiedGerman->addProperty(new PropertyDefinition('yellowishYugoslavian', new StringType()));

        $this->pointedZebra = new ClassType('PointedZebra', 'Wookieb\Tests\Resources\PointedZebra');
        $this->pointedZebra->addProperty(new PropertyDefinition('mundaneInsurance', new StringType()));
        $this->pointedZebra->addProperty(new PropertyDefinition('knobbyXylophone', new IntegerType()));
        $this->pointedZebra->addProperty(new PropertyDefinition('putridValue', new BooleanType()));

        $this->intentionalQuiver = new ClassType('IntentionalQuiver', 'Wookieb\Tests\Resources\IntentionalQuiver', $this->pointedZebra);
        $this->intentionalQuiver->addProperty(new PropertyDefinition('knobbyXylophone', $this->qualifiedGerman));
    }

    public function testClassTypeNameCannotBeEmpty()
    {
        $this->cannotBeEmpty('Name of class type cannot be empty');
        new ClassType('', 'Wookieb\Tests\Resources\QualifiedGerman');
    }

    public function testClassNameCannotBeEmptyToo()
    {
        $this->cannotBeEmpty('Class name cannot be empty');
        new ClassType('QualifiedGerman', '');
    }

    public function testGetName()
    {
        $this->assertSame('IntentionalQuiver', $this->intentionalQuiver->getName());
    }

    public function testGetParentType()
    {
        $this->assertSame($this->pointedZebra, $this->intentionalQuiver->getParentType());
    }

    public function testGetClass()
    {
        $this->assertSame('Wookieb\Tests\Resources\IntentionalQuiver', $this->intentionalQuiver->getClass());
    }

    public function testIsTargetType()
    {
        $object = new IntentionalQuiver();
        $this->assertTrue($this->intentionalQuiver->isTargetType($object));
        $this->assertFalse($this->intentionalQuiver->isTargetType('Wookieb\Tests\Resources\IntentionalQuiver'));
    }

    public function testCreateObjectWithoutParentAndPropertiesWithoutObject()
    {
        $result = $this->qualifiedGerman->create(array('yellowishYugoslavian' => 'tiny yard'));
        $this->assertInstanceOf('Wookieb\Tests\Resources\QualifiedGerman', $result);
        $this->assertAttributeSame('tiny yard', 'yellowishYugoslavian', $result);
    }

    public function testCreateObjectWithParentAndPropertiesWithObject()
    {
        $result = $this->intentionalQuiver->create(array(
            'knobbyXylophone' => array(
                'yellowishYugoslavian' => 'knowledgeable muscle'
            ),
            'mundaneInsurance' => 'zealous albatross',
            'putridValue' => false
        ));

        $this->assertInstanceOf('Wookieb\Tests\Resources\IntentionalQuiver', $result);
        $this->assertAttributeSame('zealous albatross', 'mundaneInsurance', $result);
        $this->assertAttributeSame(false, 'putridValue', $result);
        $this->assertAttributeInstanceOf('Wookieb\Tests\Resources\QualifiedGerman', 'knobbyXylophone', $result);
        $this->assertAttributeSame('knowledgeable muscle', 'yellowishYugoslavian', $result->knobbyXylophone);
    }

    public function testCreateObjectUsingSetter()
    {
        $properties = $this->pointedZebra->getProperties();
        $properties['mundaneInsurance']->setSetterName('setMundaneInsurance');
        $properties['knobbyXylophone']->setSetterName('setManama');

        $result = $this->pointedZebra->create(array(
            'mundaneInsurance' => 'jumpy uganda',
            'knobbyXylophone' => '11',
            'putridValue' => 'true'
        ));

        $this->assertInstanceOf('Wookieb\Tests\Resources\PointedZebra', $result);
        $this->assertAttributeSame('jumpy uganda - changed by setter', 'mundaneInsurance', $result);
        $this->assertAttributeSame(11, 'knobbyXylophone', $result, 'should fallback to set property by reflection');
        $this->assertAttributeSame(true, 'putridValue', $result);
    }

    public function testCreateObjectByCreatingPublicProperty()
    {
        $this->qualifiedGerman->addProperty(new PropertyDefinition('youngFiction', new IntegerType(8)));

        $data = new \stdClass();
        $data->yellowishYugoslavian = 'yep, he is yellowish';
        $data->youngFiction = 100;

        $result = $this->qualifiedGerman->create($data);

        $this->assertInstanceOf('Wookieb\Tests\Resources\QualifiedGerman', $result);
        $this->assertAttributeSame('yep, he is yellowish', 'yellowishYugoslavian', $result);
        $this->assertAttributeSame(100, 'youngFiction', $result);
    }

    public function testShouldThrowExceptionWhenPropertyCannotBeSet()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Cannot set value for property "yellowishYugoslavian"');
        try {
            $this->qualifiedGerman->create(array('yellowishYugoslavian' => array()));
        } catch (InvalidValueException $e) {
            $this->assertContains('Invalid data to create a string', $e->getPrevious()->getMessage());
            throw $e;
        }
    }

    public function testCreateWithDefaultValueOfProperty()
    {
        $properties = $this->qualifiedGerman->getProperties();
        $properties['yellowishYugoslavian']->setDefaultValue('young fiction');
        $result = $this->qualifiedGerman->create(array());
        $this->assertInstanceOf('Wookieb\Tests\Resources\QualifiedGerman', $result);
        $this->assertAttributeSame('young fiction', 'yellowishYugoslavian', $result);
    }

    public function testCreateShouldAcceptOnlyTraversableData()
    {
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Invalid data to create object');
        $this->qualifiedGerman->create(false);
    }

    public function testBecauseFuckYouPhp()
    {
        $object = new ClassType('Exception', '\Exception');
        $object->addProperty(new PropertyDefinition('message', new StringType()));

        $result = $object->create(array('message' => 'previous message'));
        $this->assertInstanceOf('\Exception', $result);
        $this->assertSame('previous message', $result->getMessage());
    }

    public function testCreateWithTargetObjectType()
    {
        $object = new QualifiedGerman();
        $result = $this->qualifiedGerman->create($object);
        $this->assertSame($object, $result);
    }

    public function testExtractSimpleObject()
    {
        $expected = array(
            'yellowishYugoslavian' => 'primary captain'
        );
        $result = $this->qualifiedGerman->extract(new QualifiedGerman());
        $this->assertSame($expected, $result);
    }

    public function testExtractObjectWithParent()
    {
        $expected = array(
            'knobbyXylophone' => array(
                'yellowishYugoslavian' => 'primary captain'
            ),
            'mundaneInsurance' => '',
            'putridValue' => false
        );

        $object = new IntentionalQuiver();
        $object->knobbyXylophone = new QualifiedGerman();
        $object->knobbyXylophone->yellowishYugoslavian = 'primary captain';

        $this->assertSame($expected, $this->intentionalQuiver->extract($object));
    }

    public function testExtractWithGetter()
    {
        $properties = $this->pointedZebra->getProperties();
        $properties['mundaneInsurance']->setGetterName('getMundaneInsurance');

        $expected = array(
            'mundaneInsurance' => ' - changed by getter',
            'knobbyXylophone' => 0,
            'putridValue' => false
        );

        $object = new PointedZebra();
        $this->assertSame($expected, $this->pointedZebra->extract($object));
    }

    public function testExtractWhenSomePropertyIsInvalid()
    {
        $this->qualifiedGerman->addProperty(new PropertyDefinition('variableDesk', new StringType()));
        $this->setExpectedException('Wookieb\ZorroDataSchema\Exception\InvalidValueException', 'Cannot extract value of property "variableDesk"');
        try {
            $this->qualifiedGerman->extract(new QualifiedGerman());
        } catch (InvalidValueException $e) {
            $this->assertContains('Cannot extract data for property "variableDesk" since it not exist', $e->getPrevious()->getMessage());
            throw $e;
        }
    }
}
