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

        if (empty($groups)) {
            $this->setGroupsFromStorage();
        } else {
            $this->setGroups($groups);
        }
    }

    public function setGroupsFromStorage()
    {
        $this->setGroups($this->mongo->getGroups());
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

    public function addMember(Member $member)
    {
        $group = $this->groups[0];

        $group->addMember($member);
        $this->mongo->saveGroup($group);
    }
}
