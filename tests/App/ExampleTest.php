<?php

namespace Test\App;

use App\Example;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testExample(): void
    {
        $example = new Example();

        $result = $example->get();

        self::assertEquals(10, $result);

    }
}