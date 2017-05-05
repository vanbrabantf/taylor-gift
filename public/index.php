<?php
require_once('vendor/autoload.php');

$loop = ReactEventLoopFactory::create();

$client = new SlackRealTimeClient($loop);
$client->setToken('token here');
$client->connect();

$loop->run();
