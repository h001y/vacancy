<?php

namespace Application\Loader;

use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    protected $loader;

    protected function setUp(): void
    {
        $this->loader = new Loader;
    }

    public function testValidate()
    {
        $this->assertSame(true, $this->loader->isValidate('GEV'));
    }

}
