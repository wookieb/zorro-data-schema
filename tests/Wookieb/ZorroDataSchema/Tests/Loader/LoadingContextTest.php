<?php

namespace Wookieb\ZorroDataSchema\Tests\Loader;

use Wookieb\ZorroDataSchema\Loader\LoadingContext;
use Wookieb\ZorroDataSchema\Tests\ZorroUnit;

class LoadingContextTest extends ZorroUnit
{
    /**
     * @var LoadingContext
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new LoadingContext();
    }

    public function testAddResources()
    {
        $resource = $this->getMockForAbstractClass('Symfony\Component\Config\Resource\ResourceInterface');
        $this->assertMethodChaining($this->object->addResource($resource), 'addResource');
        $this->assertEquals(array($resource), $this->object->getResources());
    }
}
