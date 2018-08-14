<?php
declare(strict_types=1);

namespace LeaderBoard;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager as MongoManager;


class MongoStorage
{

    const COLLECTION_NAME = "leaderboard";
    const SEQUENCE_NAME = "sequence";


    private $mongo;
    private $collection;

    private $sequenceCollection;

    public function __construct(array $config = null)
    {
        $this->mongo = new MongoManager('mongodb://' . $config['host'] . ':' . $config['port']);
        $this->collection = $config['db'] . '.' . self::COLLECTION_NAME;
        $this->sequenceCollection = $config['db'] . '.' . self::SEQUENCE_NAME;
    }

    public function tryWrite(): bool
    {
        $bulk = new BulkWrite();

        $bulk->update(['_id' => 1], array_merge(['_id' => 1], ["a"=>2, "b"=>3]), ['upsert' => true]);

        $result = $this->mongo->executeBulkWrite($this->collection, $bulk);

        return $result->isAcknowledged();
    }

    public function saveGroup(Group $group): bool
    {
        $bulk = new BulkWrite();

        $bulk->update(['id' => $group->getId()], $group->toArray(), ['upsert' => true]);

        $result = $this->mongo->executeBulkWrite($this->collection, $bulk);

        return $result->isAcknowledged();
    }

    public function getNextGroupId(): int
    {



    }

}
