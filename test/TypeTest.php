<?php
declare(strict_types=1);

namespace test;

use LeaderBoard\Type;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function test__construct()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Type('abrakadabra');
    }

    public function testToString()
    {
        $type = new Type(Type::KIT);
        $this->assertEquals("kit", $type->toString());
    }
}
