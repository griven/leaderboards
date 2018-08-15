<?php
declare(strict_types=1);

namespace LeaderBoard;


class LeaderBoard
{
    private $mongo;

    private $groups;

    public function __construct(MongoStorage $mongo)
    {
        $this->mongo = $mongo;
    }

    public function setGroupsFromStorage(): iterable
    {
        $this->groups = $this->mongo->getGroups();
    }

    public function setGroups(iterable $groups)
    {
        $this->groups = $groups;
    }

    public function writeGroups(): bool
    {
        $saved = true;
        foreach ($this->groups as $group) {
            $saved &= $this->mongo->saveGroup($group);
        }

        return $saved;
    }
}
