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

        $container->set($name1 = 'name', $value1 = '5');
        self::assertEquals($value1, $container->get($name1));

        $container->set($name2 = 'name', $value2 = true);
        self::assertEquals($value2, $container->get($name2));

        $container->set($name3 = 'name', $value3 = ['array']);
        self::assertEquals($value3, $container->get($name3));

        $container->set($name4 = 'name', $value4 = new \stdClass());
        self::assertEquals($value4, $container->get($name4));
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

    public function testContainerPass()
    {
        $container = new Container();

        $container->set('param', $value = 15);
        $container->set($name = 'name', function (Container $container){
            $obj = new \stdClass();
            $obj->param = $container->get('param');
            return $obj;
        });

        self::assertObjectHasAttribute('param', $container->get($name));
        self::assertEquals($value, $container->get('name')->param);
    }

    public function testSingleton()
    {
        $container = new Container();

        $container->set($name = 'name', function (){
            return new \stdClass();
        });

        $value1 = $container->get($name);
        $value2 = $container->get($name);

        self::assertNotNull($value1);
        self::assertNotNull($value2);
        self::assertSame($value1, $value2);
    }

}