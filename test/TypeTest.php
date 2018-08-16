<?php
declare(strict_types=1);

namespace LeaderBoard;

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
        $type = new Type(Type::WHALE);
        $this->assertEquals(Type::WHALE, $type->toString());
    }

    public function testEquals()
    {
        $type = new Type(Type::WHALE);
        $type2 = new Type(Type::WHALE);
        $type3 = new Type(Type::DEFAULT);

        $this->assertTrue($type->equals($type2));
        $this->assertFalse($type->equals($type3));
    }
}
