<?php

require "vendor/autoload.php";

// docker exec -it e1b7055333f2 mongo localhost/local

$config = [
    "host" => "mongo",
    "port" => 27017,
    "db" => "local",
];

$mongo = new \LeaderBoard\MongoStorage($config);

$leaderBoard = new \LeaderBoard\LeaderBoard($mongo);
$leaderBoard->getGroupsFromStorage();
//$leaderBoard->writeGroups();
