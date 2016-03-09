<?php

namespace Thapp\Jmg\Loader\Dropbox\Tests;

use Thapp\Jmg\Loader\Dropbox\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceOf('Thapp\Jmg\Loader\LoaderInterface', new Loader($this->mockClient()));
    }

    private function mockClient()
    {
        return $this->getMockbuilder('Dropbox\Client')
            ->disableOriginalConstructor()->getMock();
    }
}
