<?php
declare(strict_types=1);

namespace LeaderBoard;


class LeaderBoard
{
    private $kitGroup;

    private $mongo;

    public function __construct(MongoStorage $mongo)
    {
        $this->mongo = $mongo;
    }

    public function writeGroups(): bool
    {
        $this->kitGroup = new Group($this->mongo->getNextGroupId(), new Type(Type::KIT));

        return $this->mongo->saveGroup($this->kitGroup);
    }
}
