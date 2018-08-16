<?php

require "vendor/autoload.php";

// docker exec -it leaderboards_mongo_1 mongo localhost/local

// db.leaderboard.find({}, {_id:0, "id":1, "members.type":1, "members.id":1})

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

$types = [
    new Type(Type::WHALE),
    new Type(Type::PAYER),
    new Type(Type::DEFAULT)
];

for($i=0;$i<1000;$i++) {

    $percent = rand(0,100);
    if ($percent<5) {
        $type = $types[0];
    } elseif ($percent<30) {
        $type = $types[1];
    } else {
        $type = $types[2];
    }

    $leaderBoard->distributeMember(new Member($type, $mongo->getNextId("member"),1,0));

    if ($i % 100 === 0) {
        echo $i . PHP_EOL;
    }
}

echo $leaderBoard->toString();
