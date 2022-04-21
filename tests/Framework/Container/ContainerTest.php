<?php

namespace Framework\Container;


use Framework\Container\ServiceNotFoundException;
use Framework\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testPrimitives(): void
    {
        $container = new Container();

        $container->set($name = 'name', $value = '5');
        self::assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = true);
        self::assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = ['array']);
        self::assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = new \stdClass());
        self::assertEquals($value, $container->get($name));
    }

    public function testNotFound(): void
    {
        $container = new Container();

        $this->expectException(ServiceNotFoundException::class);

        $container->get('email');
    }

    public function testCallback()
    {
        $container = new Container();

        $container->set($name = 'name', function (){
            return new \stdClass();
        });

        self::assertNotNull($container->get('name'));
        self::assertInstanceOf(\stdClass::class, $container->get('name'));

    }

}