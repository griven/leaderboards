<?php
declare(strict_types=1);

namespace LeaderBoard;

use PHPUnit\Framework\TestCase;

class LeaderBoardTest extends TestCase
{
    private $whaleGroup;
    private $payerGroup;
    private $defaultGroup;

    public function setUp()
    {
        $memCollection = new MembersCollection();

        $this->whaleGroup = new Group(1, new Type(Type::WHALE), $memCollection);
        $this->payerGroup = new Group(2, new Type(Type::PAYER), $memCollection);
        $this->defaultGroup = new Group(3, new Type(Type::DEFAULT), $memCollection);
    }

    public function testSortGroups()
    {
        $groups = [$this->defaultGroup, $this->whaleGroup, $this->payerGroup, $this->whaleGroup];
        $expectedGroups = [$this->whaleGroup, $this->whaleGroup, $this->payerGroup,$this->defaultGroup];

        $leaderBoard = new LeaderBoard($this->createMock(MongoStorage::class), $groups);
        $leaderBoard->sortGroups();
        $sortedGroups = $leaderBoard->getGroups();

        $this->assertEquals($expectedGroups, $sortedGroups);
    }

    public function groupToIntForSortProvider()
    {
        $memCollection = new MembersCollection();

        return [
            [2, new Group(1, new Type(Type::WHALE), $memCollection)],
            [1, new Group(2, new Type(Type::PAYER), $memCollection)],
            [0, new Group(3, new Type(Type::DEFAULT), $memCollection)],
        ];
    }

    /**
     * @dataProvider groupToIntForSortProvider
     * @param int $expected
     * @param Group $group
     */
    public function testGroupToIntForSort(int $expected, Group $group)
    {
        $sortNumber = LeaderBoard::groupToIntForSort($group);

        $this->assertEquals($expected, $sortNumber);
    }
}
