<?php
declare(strict_types=1);

namespace LeaderBoard;


class MembersCollection implements IArrayTransform
{
    /** @var Member[] $members */
    private $members;

    public function __construct(array $members = [])
    {
        // валидация
        $this->members = [];
        foreach ($members as $member) {
            $this->addMember($member);
        }
    }

    public function getCount(): int
    {
        return count($this->members);
    }

    public function toArray(): array
    {
        $membersArray = [];
        foreach ($this->members as $member) {
            $membersArray[] = $member->toArray();
        }
        return $membersArray;
    }

    public static function fromArray(array $data): self
    {
        $members = [];
        foreach ($data as $memberData) {
            $members[] = Member::fromArray($memberData);
        }

        return new self($members);
    }

    /**
     * Добавление участника
     *
     * @param Member $member
     * @return bool - true(добавили), false(нет места)
     * @throws LeaderBoardException
     */
    public function addMember(Member $member): bool
    {
        if (in_array($member, $this->members)) {
            throw new LeaderBoardException("already in collection, member Id " . $member->getId());
        }

        $this->members[] = $member;
        return true;
    }

    public function getCountByType(Type $type): int
    {
        $count = 0;
        foreach ($this->members as $member) {
            if ($member->getType()->equals($type)) {
                $count++;
            }
        }

        return $count;
    }

    public function isFull()
    {
        return $this->getCount() >= MemberLimit::TOTAL_LIMIT;
    }
}
