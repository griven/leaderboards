<?php

require "vendor/autoload.php";

// docker exec -it leaderboards_mongo_1 mongo localhost/local

use LeaderBoard\MongoStorage;
use LeaderBoard\LeaderBoard;
use LeaderBoard\Member;
use LeaderBoard\Type;

$config = [
    "host" => "mongo",
    "port" => 27017,
    "db" => "local",
];

$mongo = new MongoStorage($config);

$leaderBoard = new LeaderBoard($mongo);

$leaderBoard->distributeMember(new Member(new Type(Type::WHALE), $mongo->getNextId("member"),1,0));
//$leaderBoard->writeGroups();
