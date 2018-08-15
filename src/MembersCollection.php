<?php
declare(strict_types=1);

namespace LeaderBoard;


class MembersCollection implements IArrayTransform
{
    /** @var Member[] $members */
    private $members;

    public function __construct(array $members)
    {
        // валидация
        foreach ($members as $member) {
            if ($member instanceof Member) {
                $this->members = $members;
            } else {
                $msg = (gettype($member) == 'object') ? "class " . get_class($member) : "type " . gettype($member);
                throw new \InvalidArgumentException("bad Member " . $msg);
            }
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

    public function addMember(Member $member)
    {
        $this->members[] = $member;
    }
}
