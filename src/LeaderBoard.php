<?php
declare(strict_types=1);

namespace LeaderBoard;


class LeaderBoard
{
    /** @var MongoStorage $mongo */
    private $mongo;

    /** @var Group[] $groups */
    private $groups;

    public function __construct(MongoStorage $mongo, iterable $groups = [])
    {
        $this->mongo = $mongo;

        $this->groups = [];
        if (empty($groups)) {
            $this->setGroupsFromStorage();
        } else {
            $this->setGroups($groups);
        }
    }

    public function setGroupsFromStorage()
    {
        $this->setGroups($this->mongo->getGroups(false));
    }

    public function setGroups(iterable $groups)
    {
        foreach ($groups as $group) {
            if ($group instanceof Group) {
                $this->groups[] = $group;
            } else {
                throw new \InvalidArgumentException("bad group Type" . json_encode($group));
            }
        }
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public static function groupToIntForSort(Group $group)
    {
        switch ($group->getType()) {
            case Type::WHALE:
                return 2;
            case Type::PAYER:
                return 1;
            case Type::DEFAULT:
            default:
                return 0;
        }
    }

    public function sortGroups()
    {
        usort($this->groups, function(Group $groupA, Group $groupB) {
            $sortByType = self::groupToIntForSort($groupB) <=> self::groupToIntForSort($groupA);
            if ($sortByType === 0) {
                return $groupA->getId() <=> $groupB->getId();
            }

            return $sortByType;
        });
    }

    /**
     * Распределяем участника в группу
     *
     * @param Member $member
     * @throws \Exception
     */
    public function distributeMember(Member $member)
    {
        $this->sortGroups();

        $group = $this->addToExistingGroup($member) ?? $this->addToNewGroup($member);

        $this->mongo->saveGroup($group);
    }

    /**
     * Добавление в существующую группу
     *
     * @param Member $member
     * @return Group|null
     */
    private function addToExistingGroup(Member $member): ?Group
    {
        foreach ($this->groups as $group) {
            $isAdded = $group->addMember($member);
            if ($isAdded) {
                return $group;
            }
        }

        return null;
    }

    /**
     * Добавление в новую группу
     *
     * @param Member $member
     * @return Group
     * @throws \Exception
     */
    private function addToNewGroup(Member $member): Group
    {
        $group = Group::createForMember($this->mongo->getNextId("group"), $member);
        $this->groups[] = $group;
        return $group;
    }

    public function toString()
    {
        $string = 'Leaderboard' . PHP_EOL;
        foreach ($this->groups as $group) {
            $groupArrayInfo = array_merge([$group->getId(), $group->getType()] , $group->getCount());
            $string .= implode(" | ", $groupArrayInfo) . PHP_EOL;
        }

        return $string;
    }
}
