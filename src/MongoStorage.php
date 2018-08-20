<?php
declare(strict_types=1);

namespace LeaderBoard;

use MongoDB\Model\BSONDocument;

class MongoStorage
{

    const COLLECTION_PLAYERS = "leaderboard_players";
    const COLLECTION_GROUPS = "leaderboard_groups";
    const SEQUENCE_NAME = "sequence";

    /** @var \MongoDB\Collection  */
    private $playersCollection;

    /** @var \MongoDB\Collection  */
    private $groupsCollection;

    /** @var \MongoDB\Collection  */
    private $sequenceCollection;

    public function __construct(array $config = null)
    {
        $mongo = (new \MongoDB\Client('mongodb://' . $config['host'] . ':' . $config['port']));

        $this->playersCollection = $mongo->selectDatabase($config['db'])->selectCollection(self::COLLECTION_PLAYERS);
        $this->groupsCollection = $mongo->selectDatabase($config['db'])->selectCollection(self::COLLECTION_GROUPS);
        $this->sequenceCollection = $mongo->selectDatabase($config['db'])->selectCollection(self::SEQUENCE_NAME);
    }

    public function insertGroup(Group $group): bool
    {
        $result = $this->groupsCollection->insertOne($group->toArray());

        return $result->isAcknowledged();
    }

    public function getNextId(string $collection): int
    {
        $result = $this->sequenceCollection->findOneAndUpdate(
            ['name' => $collection],
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

        throw new \Exception("can't find next id");
    }

    public function getGroups(bool $isFull = null): iterable
    {
        $query = is_null($isFull) ? [] : ["isFull" => $isFull];

        $groupsData = $this->groupsCollection->find($query, [
            'typeMap' => [
                'root'     => 'array',
                'document' => 'array'
            ]
        ]);

        $data = $groupsData->toArray();

        $groups = [];

        foreach ($data as $groupData) {
            $groupData["members"] = MembersCollection::fromArray($this->findMembers($groupData["id"]));
            $groups[] = Group::fromArray($groupData);
        }

        return $groups;
    }

    private function findMembers(int $groupId): array
    {
        $cursor = $this->playersCollection->find(
            ["group_id" => $groupId],
            [
                'typeMap' => [
                    'root'     => 'array',
                    'document' => 'array'
                ]
            ]
        );

        return $cursor->toArray();
    }

    public function addMemberToGroup(Member $member, Group $group): bool
    {
        // проверяем есть ли место в группе

        $typeCount = $member->getType()->toString();

        /** @var BSONDocument $result */
        $result = $this->groupsCollection->findOneAndUpdate(
            ["id" => $group->getId()],
            ['$inc' => ["count.$typeCount" => 1]],
            [
                // наркомания для того, чтобы документ возвращался уже обновлённый
                'returnDocument' => 2,
                'upsert' => true,
                'typeMap' => [
                    'root'     => 'array',
                    'document' => 'array'
                ],
            ]
        );

        $canInsert = $result["count"][$typeCount] <= $group->getLimit($member->getType());
        if (!$canInsert) {
            $totalCount = 0;
            foreach ($result["count"] as $counts) {
                $totalCount += $counts;
            }
            if ($totalCount >= MemberLimit::TOTAL_LIMIT) {
                $this->groupsCollection->updateOne(
                    ["id" => $group->getId()],
                    ['$set' => ["isFull" => true]]
                );
            }

            return false;
        }

        // добавляем в группу
        $memberData = array_merge(["group_id" => $group->getId()], $member->toArray());
        return $this->playersCollection->insertOne($memberData)->isAcknowledged();
    }
}
