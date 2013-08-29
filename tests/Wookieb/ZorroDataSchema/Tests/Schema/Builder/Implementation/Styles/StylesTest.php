<?php

namespace Wookieb\ZorroDataSchema\Tests\Schema\Builder\Implementation\Style;

use Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\Styles;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class StylesTest extends ZorroUnit
{
    /**
     * @var Styles
     */
    protected $object;

    private $style;

    protected function setUp()
    {
        $this->object = new Styles();

        $this->style = $this->getMockForAbstractClass('Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style\StyleInterface');
    }

    public function testRegisterStyle()
    {
        $name = 'ironclad-wrist';
        $this->assertMethodChaining($this->object->registerStyle($name, $this->style), 'registerStyle');
        $this->assertSame($this->style, $this->object->getStyle($name));
        $this->assertTrue($this->object->hasStyle($name));
    }

    public function testStyleNameCannotBeEmpty()
    {
        $this->cannotBeEmpty();
        $this->object->registerStyle('', $this->style);
    }

    public function testExceptionWhenAttemptToGetStyleThatDoesNotExists() {
        $this->notExists();
        $this->object->getStyle('ironclad-wrist');
    }
}
