<?php

namespace Application;

use PHPUnit\Framework\TestCase;

class CoreTest extends TestCase
{
    protected $core;

    protected function setUp(): void
    {

        $this->core = new Core;
    }

    public function testValidate()
    {
        $this->assertSame(true, $this->loader->isValidate('GEV'));
    }

}
