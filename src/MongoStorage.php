<?php
declare(strict_types=1);

namespace LeaderBoard;

class MongoStorage
{

    const COLLECTION_NAME = "leaderboard";
    const SEQUENCE_NAME = "sequence";

    private $collection;

    private $sequenceCollection;

    public function __construct(array $config = null)
    {
        $mongo = (new \MongoDB\Client('mongodb://' . $config['host'] . ':' . $config['port']));

        $this->collection = $mongo->selectDatabase($config['db'])->selectCollection(self::COLLECTION_NAME);
        $this->sequenceCollection = $mongo->selectDatabase($config['db'])->selectCollection(self::SEQUENCE_NAME);
    }

    public function tryWrite(): bool
    {
        $insertOneResult = $this->collection->insertOne([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);

        return $insertOneResult->isAcknowledged();
    }

    public function saveGroup(Group $group): bool
    {
        $result = $this->collection->updateOne(['id' => $group->getId()], ['$set' => $group->toArray()], ['upsert' => true]);

        return $result->isAcknowledged();
    }

    public function getNextGroupId(): int
    {
        $result = $this->sequenceCollection->findOneAndUpdate(
            ['name' => "group"],
            ['$inc' => ['value' => 1]],
            [
                // наркомания для того, чтобы документ возвращался уже обновлённый
                'returnDocument' => 2,
                'upsert' => true
            ]
        );
        if (!empty($result)) {
            return (int)$result->value;
        }

        throw new \Exception("can't find nex group id");
    }

    public function getGroups(): iterable
    {
        $groupsData = $this->collection->find();

        $groups = [];
        foreach ($groupsData as $groupData) {
            $groups[] = Group::fromArray($groupData);
        }

        return $groups;
    }

}
