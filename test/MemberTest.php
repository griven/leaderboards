<?php
declare(strict_types=1);

namespace LeaderBoard;

use PHPUnit\Framework\TestCase;

class MemberTest extends TestCase
{
    /** @var Member $member */
    private $member;

    protected function setUp()
    {
        $this->member = new Member(new Type(Type::DEFAULT), 1,1,0);
    }

    public function test__construct()
    {
        $this->assertInstanceOf(Member::class, $this->member);
    }

    public function testGetType()
    {
        $this->assertInstanceOf(Type::class, $this->member->getType());
    }

    public function testToArray()
    {
        $expected = [
            "type" => Type::DEFAULT,
            "id" => 1,
            "level" => 1,
            "score" => 0,
        ];

        $result = $this->member->toArray();

        $this->assertEquals($expected, $result);
    }

    public function testFromArray()
    {
        $data = [
            "type" => Type::DEFAULT,
            "id" => 1,
            "level" => 1,
            "score" => 0,
        ];

        $member = Member::fromArray($data);

        $this->assertEquals($this->member, $member);
        $this->assertNotSame($this->member, $member);
    }
}
